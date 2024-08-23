<?php

namespace App\Http\Controllers;

use App\Enums\PostReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachements;
use App\Models\PostReaction;
use Illuminate\Contracts\Validation\Rule;
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

    
    DB::beginTransaction();
    $attachmentsPaths = [];
    try {
        $post = Post::create($data);
        
        $files = $data['attachments'] ?? [];
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
        
        
        $user = $request->user();
        
        
        DB::beginTransaction();
        try {
            $attachmentsPaths = [];
            $data = $request->validated();
            $post->update($data);

            $deleted_ids = $data["deleted_file_ids"] ?? [];
            $attachments = PostAttachements::query()
                        ->where('post_id' ,$post->id )
                        ->whereIn('id' , $deleted_ids)
                        ->get();

            foreach ($attachments as $attachment ) {
                    $attachment->delete();
            }
            $files = $data['attachments'] ?? [];     


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

    public function downloadAttachment(PostAttachements $attachment)  {
        
        //TODO check the user if he has the permisson to download the attachmenet
        return response()->download(Storage::disk('public')->path($attachment->path), $attachment->name);

    }
    public function postReaction(Request $request, Post $post)
    {
        $data = $request->validate([
            'reaction' => 'required|in:Like,Dislike,Love', // Adjust validation rules
        ]);
        $user_id = auth()->id();
        $reaction = PostReaction::where('user_id',$user_id)->where('post_id', $post->id)->first();
        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;

            PostReaction::create([
                'post_id' => $post->id,
                'user_id' =>$user_id, // Assuming user authentication
                'type' => $data['reaction']
            ]);
            
        }      $reactions =  PostReaction::where('post_id',$post->id)->count();
    
        return response([
            'num_of_reaction' => $reactions,
            'current_user_has_reaction'=> $hasReaction,
        ]);
    }
    
    function CreateComment(Request $request , Post $post)  {
        $data = $request->validate([
            'comment'=> ['required'],
        ]);

      $comment = Comment::create([
            'user_id' => Auth::id(),
            'comment' => nl2br($data['comment']) ,
            'post_id' => $post->id
        ]);

        return response(new CommentResource($comment) , 201);
    }
}
    

