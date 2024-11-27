<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Resources\PostAttachementResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Models\Follower;
use App\Models\Post;
use App\Models\PostAttachements;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    
     public function index(Request $request , $username) {

        $isCurrentUserFollower = false;
        $user = User::where('username', $username)->first();       
        if (!Auth::guest()) {
            $isCurrentUserFollower = Follower::where('user_id' ,$user->id)->where( 'follower_id' ,Auth::id())->exists();
        }
        $follwerCount =  Follower::where('user_id' ,$user->id )->count();
        $posts = Post::PostFroTimeLine(Auth::id())
                ->where('user_id' , $user->id)
                ->paginate(10);
            
        $posts =  PostResource::collection($posts);
        if($request->wantsJson()){
            return $posts;
        }
       // Fetch followers
            $followers = $user->Followers;

            // Fetch following
            $following = $user->Following;

        $Photos = PostAttachements::query()
                        ->where('mime' , 'like' , 'image/%')
                        ->where('created_by' , $user->id)
                        ->latest()
                        ->get();

        return Inertia::render('Profile/View', [
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'success' => session('success'),
            'user' => new UserResource($user),
            'isCurrentUserFollower' => $isCurrentUserFollower,
            'follwerCount' => $follwerCount,
            'posts' =>$posts,
            'followers' => UserResource::collection($followers),
            'following' => UserResource::collection($following),
            'Photos' => PostAttachementResource::collection($Photos),

        ]);
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return to_route('profile',$request->user())->with('success','Your Profile has been updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function UpdateImages(Request $request)  {
        $data = $request->validate([
            'cover' => ['nullable','image'],
            'avatar' => ['nullable','image'],
        ]);
        $user = $request->user();
        $avatar = $data['avatar'] ?? null;
        /**  @var Illuminate\Http\UploadedFile $cover; */
        $cover = $data['cover'] ?? null;
        $success = '';
        if ($cover) {
            if ($user->cover_path) {
                Storage::disk('public')->delete($user->cover_path);
            }
            $path = $cover->store('user-'.$user->id,'public');
            $user->update(['cover_path' => $path]);
            $success = 'cover image has been updated';

        }
        if ($avatar) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $avatar->store('user-'.$user->id,'public');
            $user->update(['avatar_path' => $path]);
            $success =  'avatar image has been updated';

        }

        return back()->with('success',$success);
    }
}
