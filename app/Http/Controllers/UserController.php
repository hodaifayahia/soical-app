<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use App\Notifications\FollowUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function follow(Request $request,  User $user)  {

        $data = $request->validate([
            'follow'=>['boolean'],
        ]);

        if ($data['follow']) {
            $message = "you are following ".$user->name. "";
            Follower::create([
                'user_id' =>$user->id,
                'follower_id'=>Auth::id(),
            ]);


        }else{
            $message = "you are unfollowing ".$user->name. "";
            Follower::query()
            ->where('user_id',$user->id)
            ->where('follower_id',Auth::id())
            ->delete();
        }
        $this->notify(new FollowUser(Auth::getUser(),$data['follow']));

        
        return back()->with('success',$message);
    }
}
