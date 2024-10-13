<?php

namespace App\Http\Controllers;

use App\Enums\GroupRoleEnum;
use App\Enums\GroupStatusEnum;
use App\Http\Requests\InviteUserRequest;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupeUser;
use App\Notifications\InvitactionApproved;
use App\Notifications\InvitationGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Pest\Support\Str;
use App\Notifications\RequestToJoinToGroup;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function profile(Group $group)
    {
        $group->load('currectUserGroup');
        return Inertia::render('group/View', [
            'success' => session('success'),
            'group' => new GroupResource($group),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGroupRequest $request)
    {
        
        $data = $request->validated();

        $data['user_id'] = Auth::id();
        dd($data);

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
        $successMassage = "you have joind to the group '".$group->name."'"
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
    


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //InviteUserRequest
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
