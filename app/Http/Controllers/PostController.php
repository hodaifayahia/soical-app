<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\PostAttachements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Pest\Support\Str;

class PostController extends Controller
{
   

public function store(StorePostRequest $request)
{
    $data = $request->validated();

    $user = $request->user();
    $attachmentsPaths = [];

    DB::beginTransaction();
    try {
        $post = Post::create($data);
        
        $files = $data['attachements'] ?? [];
    
        foreach ($files as $file) {
            $path = $file->storeAs('attachements/' . $post->id,Str::random('32') ,'public');
            $attachmentsPaths[] = $path; // Append to the array
            PostAttachements::create([
                'post_id' => $post->id,
                'name' => $file->getClientOriginalName(),
                'path' => $path, 
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_by' => $user->id,
            ]);
        }
        
        DB::commit();
        return back();
    } catch (\Throwable $th) {
        foreach ($attachmentsPaths as $path) {
            Storage::disk('public')->delete($path);
        }
        DB::rollBack();
        throw  $th;
        
    }
    return back();
}



    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // To dO 
        $id = Auth::id();

        if ($post->user_id != $id) {
            return response("You Don't have permission to delate this post",403);
        }
        $post->delete();

        return back();
    }
}
