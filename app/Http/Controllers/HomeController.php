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
        
        $posts = Post::query()
            ->withCount('reactions') // Count reactions on the post
            ->withCount('comments')  // Count comments on the post
            ->with([
                'comments' => function ($query) use ($userId) {
                    $query->withCount('reactions');
                    $query
                        ->whereNull('parent_id')
                        ->withCount('reactions')
                        ->withCount('comments')
                        ->with([
                            'reactions' => function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            },
                            'comments' => function ($query) use ($userId) {
                                $query->
                                 withCount('reactions') // Count reactions on child comments
                                 ->with([
                                    'reactions' => function ($query) use ($userId) {
                                        $query->where('user_id', $userId);
                                    }
                                ]);
                            }
                        ]);
                }
            ])
            
            ->latest() // Order posts by latest
            ->paginate(20); // Paginate the results
        
        return Inertia::render('home', [
            'posts' => PostResource::collection($posts),
        ]);
    }
}
