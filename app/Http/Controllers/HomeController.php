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

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $posts = Post::PostFroTimeLine($userId)
            ->paginate(10); // Paginate the results

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
        ]);
    }
}
