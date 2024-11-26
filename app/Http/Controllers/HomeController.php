<?php

namespace App\Http\Controllers;

use App\Enums\GroupStatusEnum;
use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Resources\UserResource;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $user = $request->user();
        $posts = Post::PostFroTimeLine($userId)
    ->leftJoin('followers as f', function ($join) use ($userId) {
        $join->on('posts.user_id', '=', 'f.user_id')
             ->where('f.follower_id', '=', $userId); 
    })
    ->leftJoin('groupe_users as gu', function ($join) use ($userId) {
        $join->on('gu.group_id', '=', 'posts.group_id')
             ->where('gu.user_id', '=', $userId); 
    })
    ->whereNull('posts.deleted_at') // Ensures posts are not soft-deleted
    ->where(function ($query) use ($userId) {
        $query->whereNotNull('f.follower_id') 
              ->orWhereNotNull('gu.group_id') // Condition for group membership
              ->orWhere('posts.user_id', '=', $userId); // Replace 1 with Auth::id() if for the current user
    })
    ->whereNot('posts.user_id',$userId)
    ->select('posts.*') // Select only posts columns
    ->paginate(10);

            $posts = PostResource::collection($posts);

            if ($request->wantsJson()) {
                return $posts;
            }
            $groups = Group::query()
            ->with('currectUserGroup')
            ->select('groups.*')
            ->join('groupe_users as gu', 'gu.group_id', '=', 'groups.id')
            ->where('gu.user_id', Auth::id())
            ->where('gu.status', GroupStatusEnum::APPROVED)
            ->orderby('gu.role')
            ->orderby('name','desc')
            ->get();
            
         
        
        return Inertia::render('home', [
            'posts' => $posts,
            'groups' => GroupResource::collection($groups),
            'following' => UserResource::collection($user->following),
        ]);
    }
}
