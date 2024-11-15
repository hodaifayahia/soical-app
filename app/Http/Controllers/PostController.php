<?php

namespace App\Http\Controllers;

use App\Enums\ReactionEnum;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostAttachements;
use App\Models\Reaction;
use App\Notifications\CommentDeleted;
use App\Notifications\PostDeleted;
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

        if ($post->group && $post->group->isAdmin($id) || $post->isOnwer($id)) {
            $post->delete();

            if(!$post->isOnwer($id)){
                $post->user->notify(new PostDeleted($post->group));
            }
            return back();

        }
            return response("You Don't have permission to delate this post",403);

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
        $reaction = Reaction::where('user_id',$user_id)->where('object_id', $post->id)->where('object_type' , Post::class)->first();
        if ($reaction) {
            $hasReaction = false;
            $reaction->delete();
        } else {
            $hasReaction = true;

            Reaction::create([
                'object_id' => $post->id,
                'object_type' => Post::class,
                'user_id' =>$user_id, // Assuming user authentication
                'type' => $data['reaction']
            ]);
            
        }      $reactions =  Reaction::where('object_id', $post->id)->where('object_type' , Post::class)->count();
    
        return response([
            'num_of_reaction' => $reactions,
            'current_user_has_reaction'=> $hasReaction,
        ]);
    }
    
    public function createComment(Request $request, Post $post)
    {
        $data = $request->validate([
            'comment' => ['required'],
            'parent_id' => ['nullable', 'exists:comments,id']
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'comment' => nl2br($data['comment']),
            'parent_id' => $data['parent_id'] ?:  null,
            'user_id' => Auth::id()
        ]);
        
        return response(new CommentResource($comment), 201);
    }

    public function deleteComment(Comment $comment)
    {
        $post = $comment->post;
        $id = Auth::id();
        if ($comment->isOnwer($id) || $post->isOnwer($id) ) {
            
            $comment->delete();
            if(!$comment->isOnwer($id)){
                $comment->user->notify(new CommentDeleted($comment , $post));
            }
            return response('', 204);
        }
        return response("You don't have permission to delete this comment.", 403);

    }
    
   public function updateComment( UpdateCommentRequest $request , Comment $comment)  {
        $data = $request->validated();
        
        $comment->update([
            'comment' => nl2br($data['comment'])
        ]);

        return new CommentResource($comment);
    }

    public function CommentReactions(Request $request, Comment $comment) {
       
            // Validate request data
            $data = $request->validate([
                'reaction' => 'required|in:Like,Dislike,Love',
            ]);
    
            $user_id = auth()->id();
    
            // Find existing reaction
            $reaction = Reaction::where('user_id', $user_id)
                                ->where('object_id', $comment->id)
                                ->where('object_type', Comment::class)
                                ->first();
    
            // Determine if user has reacted
            $hasReaction = false;
            if ($reaction) {
                $hasReaction = true;
                $reaction->delete(); // Remove existing reaction
            } else {
                // Create new reaction
                Reaction::create([
                    'object_id' => $comment->id,
                    'object_type' => Comment::class,
                    'user_id' => $user_id,
                    'type' => $data['reaction']
                ]);
            }
    
            // Count reactions for the comment
             $reactions =  Reaction::where('object_id', $comment->id)->where('object_type' , Comment::class)->count();
            // Return response with reaction count and user reaction status
            return response()->json([
                'num_of_reaction' => $reactions,
                'current_user_has_reaction' => $hasReaction,
            ]);
    
    

    }
}