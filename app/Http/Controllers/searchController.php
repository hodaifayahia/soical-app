<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Group;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class searchController extends Controller
{
    
    const POSTS_PER_PAGE = 10;

    public function search(Request $request, string $keywords)
    {
         
        if (!$keywords) {
         return  redirect(route('dashbord'));
        }
    // Trim and prepare the search term
    $search = '%' . trim($keywords) . '%';

    // Avoid running queries if the search term is empty
    if (empty(trim($search))) {
        return Inertia::render('search', [
            'posts' => [],
            'users' => [],
            'groups' => [],
        ]);
    }
        // Use query builder with prepared statements
        $users = User::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('username', 'like', $search);
            })
            ->limit(20) // Limit results for better performance
            ->get();
            
        $groups = Group::query()
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', $search)
                    ->orWhere('about', 'like', $search);
            })
            ->limit(20)
            ->get();
            
        // Use lazy loading for posts
        $posts = Post::query()
            ->where('body', 'like', $search)
            ->latest() // Order by latest first
            ->paginate(self::POSTS_PER_PAGE);
            
        // Convert to resources
        $postResources = PostResource::collection($posts);
            
        if ($request->wantsJson()) {
            return $postResources;
        }
        
        return Inertia::render('search', [
            'posts' => $postResources,
            'users' => UserResource::collection($users),
            'groups' => GroupResource::collection($groups),
            'keywords' => $keywords,
        ]);
    }

}
