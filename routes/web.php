<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




Route::get('/', [HomeController::class, 'index'])
->middleware(['auth','verified'])
->name('dashboard');
// profile use view
Route::get('/u/{User:username}', [ProfileController::class, 'index'])->name('profile');
  // group
  Route::post('/group', [GroupController::class, 'store'])
  ->name('group.create');
  
  Route::put('/group/{group:slug}', [GroupController::class, 'update'])
  ->name('group.update');
  
// group view
Route::get('/g/{group:slug}', [GroupController::class, 'profile'])->name('group.profile');

Route::get('/group/approve-invitation/{group:token}', [GroupController::class, 'ApproveInvitation'])
->name('group.approve-invitation');

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-iamges', [ProfileController::class, 'UpdateImages'])
         ->name('profile.updateimages');
         // update for group
    Route::post('/group/update-iamges/{group:slug}', [GroupController::class, 'UpdateImages'])
         ->name('group.updateimages');
         
    Route::post('/group/InvateUsers/{group:slug}', [GroupController::class, 'InviteUser'])
         ->name('group.inviteUsers');

    Route::post('/group/join/{group:slug}', [GroupController::class, 'join'])
         ->name('group.join');
    Route::post('/group/changeRole/{group:slug}', [GroupController::class, 'changeRole'])
         ->name('group.changeRole');

    Route::post('/group/join-Request/{group:slug}', [GroupController::class, 'joinRequest'])
         ->name('group.joinRequest');

   
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //posts
    Route::post('/post',[PostController::class , 'store'])->name('post.create');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}',[PostController::class , 'destroy'])->name('post.destroy');
    Route::get('/post/download/{attachment}',[PostController::class , 'downloadAttachment'])->name('post.download');
    Route::post('/post/{post}/reaction', [PostController::class, 'PostReaction'])
        ->name('post.reaction');

        //comments
    Route::post('/post/{post}/comment', [PostController::class, 'CreateComment'])
    
        ->name('comment.create');
    Route::delete('/comment/{comment}', [PostController::class, 'DeleteComment'])
        ->name('comment.delate');
    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
        ->name('comment.update');
    Route::post('/comment/{comment}/reactions', [PostController::class, 'CommentReactions'])
        ->name('comment.CommentReactions');
    
  
    }
    );

require __DIR__.'/auth.php';
