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
                // Load comments and count reactions on each comment
                'comments' => function ($query) use ($userId) {
                    $query->withCount('reactions') // Count reactions on each comment
                        ->with([
                            // Load reactions on the comment filtered by user
                            'reactions' => function ($query) use ($userId) {
                                $query->where('user_id', $userId);
                            }
                        ]);
                },
                // Load reactions on the post filtered by user
                'reactions' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }
            ])
            ->latest() // Order posts by latest
            ->paginate(20); // Paginate the results
        
        return Inertia::render('home', [
            'posts' => PostResource::collection($posts),
        ]);
    }
}
