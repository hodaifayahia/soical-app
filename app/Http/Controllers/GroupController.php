<?php

namespace App\Http\Controllers;

use App\Enums\GroupRoleEnum;
use App\Enums\GroupStatutsEnum;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        $group =  Group::create($data);

        $groupUser =[
            'status' =>GroupStatutsEnum::APPROVED,
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
    public function show(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGroupRequest $request, Group $group)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }
}
