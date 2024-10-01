<?php

namespace App\Http\Controllers;

use App\Enums\GroupRoleEnum;
use App\Enums\GroupStatutsEnum;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use App\Models\GroupeUser;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'status' =>GroupStatutsEnum::APPROVER,
            'role' =>GroupRoleEnum::ADMIN,
            'user_id'=>Auth::id(),
            'group_id'=>$group->id,
            'created_by'=>Auth::id()
        ];
        $groupUserdata =  GroupeUser::create($groupUser);
        return response(new GroupResource($group), 201);
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
