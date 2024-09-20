<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $posts = Post::query() // select * form post 
            ->withCount('reactions') // selct count form reactions
            ->with([
                'comments' => function ($query) use ($userId) {
                    $query->withCount('reactions'); // selct count form reactions // select * from comments where post (1,2..)
                      
                }, 'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId); //selct reaction in comments where in (1,1,3)
                }
            ])
            
            ->latest() // Order posts by latest
            ->paginate(1); // Paginate the results
        
        return Inertia::render('home', [
            'posts' => PostResource::collection($posts),
        ]);
    }
}
