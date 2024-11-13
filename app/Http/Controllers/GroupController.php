<?php

namespace App\Http\Controllers;

use App\Enums\GroupRoleEnum;
use App\Enums\GroupStatusEnum;
use App\Http\Requests\InviteUserRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Http\Resources\GroupUserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\GroupeUser;
use App\Models\Post;
use App\Models\User;
use App\Notifications\ChangeRoleRequest;
use App\Notifications\ChangeROllRequest;
use App\Notifications\InvitactionApproved;
use App\Notifications\InvitationGroup;
use App\Notifications\RequestApproved;
use App\Notifications\RequestToJoinToGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Pest\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile(Request $request, Group $group)
    {
        $userId = Auth::id();
        $group->load('currectUserGroup');
        if ($group->hasApprovedStatus($userId)) {
            $posts =Post::PostFroTimeLine($userId)
                    ->where('group_id' , $group->id)
                    ->paginate(10); // Paginate the results
            $posts = PostResource::collection($posts);
        }else{
           return Inertia::render('group/View',[
           'success' => session('success'),
           'group' => new GroupResource($group),
           'users'=> [],
           'posts'=> null,
           'Requests'=> [],]);
        }

        if ($request->wantsJson()) {
            return PostResource::collection($posts);
        }
        $users = User::query()->select(['users.*','gu.role','gu.status','gu.group_id'])
                        ->join('groupe_users as gu','gu.user_id','users.id')
                        ->where('gu.group_id',$group->id)
                        ->orderBy('name')
                        ->get();
                        
        $Request = $group->PenddingUsers()->orderBy('name')->get();

        return Inertia::render('group/View', [
            'success' => session('success'),
            'group' => new GroupResource($group),
            'users'=> GroupUserResource::collection($users),
            'posts'=> $posts,
            'Requests'=> UserResource::collection($Request),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        
      
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $group =  Group::create($data);


        $groupUser =[
            'status' =>GroupStatusEnum::APPROVED,
            'role' =>GroupRoleEnum::ADMIN,
            'user_id'=>Auth::id(),
            'group_id'=>$group->id,
            'created_by'=>Auth::id()
        ];
        $groupUserdata =  GroupeUser::create($groupUser);
        $group->role = $groupUser['role'];
        $group->status = $groupUser['status'];

        return response(new GroupResource($group), 201);
    }

    public function UpdateImages(Request $request , Group $group)  {

        if (!$group->isAdmin(Auth::id())) {
            return response("You Don't have permisson to preform this action "  , 403);
        }
        $data = $request->validate([
            'cover' => ['nullable','image'],
            'thumbnail' => ['nullable','image'],
        ]);
        $thumbnail = $data['thumbnail'] ?? null;
        /**  @var Illuminate\Http\UploadedFile $cover; */
        $cover = $data['cover'] ?? null;
        $success = '';
        if ($cover) {
            if ($group->cover_path) {
                Storage::disk('public')->delete($group->cover_path);
            }
            $path = $cover->store('group-'.$group->id,'public');
            $group->update(['cover_path' => $path]);
            $success = 'cover image has been updated';

        }
        if ($thumbnail) {
            if ($group->thumbnail_path) {
                Storage::disk('public')->delete($group->thumbnail_path);
            }
            $path = $thumbnail->store('group-'.$group->id,'public');
            $group->update(['thumbnail_path' => $path]);
            $success =  'thumbnail image has been updated';

        }

        return back()->with('success',$success);
    }
    /**
     * Display the specified resource.
     */
    public function InviteUser(InviteUserRequest $request, Group $group)
    {
        $data = $request->validated();
        

        $user = $request->user;
        $groupuser = $request->groupuser;
        if ($groupuser) {
            $groupuser->delete();
        }
            $hours = 24;
            $token = Str::random(256);        
        $groupUser = GroupeUser::create([
            'role' => GroupRoleEnum::USER,
            'status' =>GroupStatusEnum::PENDDING,
            'token' =>$token ,
            'token_expire_date' => Carbon::now()->addHours($hours),
            'user_id'=>$user->id ,
            'group_id' => $group->id ,
            'created_by' => Auth::id(),
        ]);
        $user->notify(new InvitationGroup($group , $hours ,$token));

            return back()->with('success','User was invite to join to group');

    }

    public function ApproveInvitation(String $token)  
    {
        $groupuser = GroupeUser::where('token', $token)->first();
        $errorTitle = '';
    
        if (!$groupuser) {
            $errorTitle = "The link is invalid.";
        } else if ($groupuser->token_used || $groupuser->status === GroupStatusEnum::APPROVED) {
            $errorTitle = "The link is already used.";
        } else if ($groupuser->token_expire_date < Carbon::now()) {
            $errorTitle = "The link has expired.";
        }
    
        if ($errorTitle) {
            // Correct the compact usage here
            return Inertia::render('Error', ['title' => $errorTitle]);
        }
    
        $groupuser->status = GroupStatusEnum::APPROVED;
        $groupuser->token_used = Carbon::now();
        $groupuser->save();
    
        $adminuser = $groupuser->adminuser;
        $adminuser->notify(new InvitactionApproved($groupuser->group, $groupuser->user));
    
        return redirect(route('group.profile', $groupuser->group))->with('success','You acceccpted to join to group',$groupuser->ma);
    }
    public function join(Group $group)  {
        $user = request()->user();

        $status = GroupStatusEnum::APPROVED;
        $successMassage = "you have joind to the group '".$group->name."'";
        if(!$group->auto_approval){
            $status = GroupStatusEnum::PENDDING;
            Notification::send($group->adminusers ,new  RequestToJoinToGroup($group , $user));
            $successMassage = "you request has been accepted you will notified once will be approved ";
        }
        $groupUser = GroupeUser::create([
            'role' => GroupRoleEnum::USER,
            'status' =>$status,
            'user_id'=>$user->id ,
            'group_id' => $group->id ,
            'created_by' => $user->id,
        ]);
        

        return back()->with('success',$successMassage );

    }
    public function joinRequest(Request $request, Group $group)  {
        if(!$group->isAdmin(Auth::id())){
            return response('You Don"t have permisson to perform this action ', 403);
        }
        $data = $request->validate([
            'user_id'=>['required'],
            'action' =>['required']
        ]);
        $groupuser = GroupeUser::where('user_id', $data['user_id'])
        ->where('group_id', $group->id)
        ->where('status', GroupStatusEnum::PENDDING)
        ->first();
    
        
        if ($groupuser) {
            $approved = false;
            if ($data['action'] ==GroupStatusEnum::APPROVED ) {
                $approved = true;
                $groupuser->status = GroupStatusEnum::APPROVED;
            }
            else{
                $groupuser->status = GroupStatusEnum::REJECTED;
            }
            
            $groupuser->save();
            $user = $groupuser->user;
            $user->notify(new RequestApproved($groupuser->group, $user ,$approved));
    
    
            // your request has been approved to the user 
            back()->with('success', 'User ',$groupuser->user->name,'was',$approved);
        }
        return back();


    } 
    


    /**
     * Update the specified resource in storage.
     */
    public function changeRole(Request $request, Group $group)
    {
        if(!$group->isOwner(Auth::id())){
            return response('You don\'t have permission to perform this action', 403);
        }
    
        $data = $request->validate([
            'user_id' => ['required'],
            'role' => ['required', Rule::in(GroupRoleEnum::values())],
        ]);
    
        $groupUser = GroupeUser::where('user_id', $data['user_id'])
            ->where('group_id', $group->id)
            ->first();
    
        $user_id = $data['user_id'];
        if ($user_id == $group->user_id) {
            return response('Cannot change the role of the group owner', 400);
        }
    
        if ($groupUser) {
            $groupUser->role = $data['role'];
            $groupUser->save();
    
            // Send notification to the user that their role was changed
            $user = $groupUser->user;
            $user->notify(new ChangeRoleRequest($groupUser->group, $user));
        }
    
        return back();
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function update(UpdateGroupRequest $request , Group $group)
    {
        $group->update($request->validated());
        return back()->with('success' , 'your Group Information has been updated ');
        
    }
    public function destroy(Group $group)
    {
        //
    }
}
