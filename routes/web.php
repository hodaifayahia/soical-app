<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\searchController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;






Route::get('/', [HomeController::class, 'index'])
->middleware(['auth','verified'])
->name('dashboard');
// profile use view
Route::get('/u/{User:username}', [ProfileController::class, 'index'])->name('profile');
  // group

Route::middleware('auth')->group(function () {


    Route::prefix('/group')->group(function(){
        Route::post('/', [GroupController::class, 'store'])
        ->name('group.create');
        
        Route::put('/{group:slug}', [GroupController::class, 'update'])
        ->name('group.update');
        
        Route::get('/g/{group:slug}', [GroupController::class, 'profile'])->name('group.profile');
        
        Route::get('/approve-invitation/{group:token}', [GroupController::class, 'ApproveInvitation'])
        ->name('group.approve-invitation');
        
        Route::delete('/remove-user/{group:slug}', [GroupController::class, 'remveUser'])
        ->name('group.remveUser');
        
        Route::post(' /update-iamges/{group:slug}', [GroupController::class, 'UpdateImages'])
            ->name('group.updateimages');
        
        Route::post(' /InvateUsers/{group:slug}', [GroupController::class, 'InviteUser'])
                ->name('group.inviteUsers');

        Route::post(' /join/{group:slug}', [GroupController::class, 'join'])
                ->name('group.join');
        Route::post(' /changeRole/{group:slug}', [GroupController::class, 'changeRole'])
                ->name('group.changeRole');

        Route::post(' /join-Request/{group:slug}', [GroupController::class, 'joinRequest'])
                ->name('group.joinRequest');
    });

    Route::prefix('/post')->group(function(){
        Route::post('/',[PostController::class , 'store'])->name('post.create');
        Route::get('/view/{post}',[PostController::class , 'view'])->name('post.view');
        Route::put('/{post}', [PostController::class, 'update'])->name('post.update');
        Route::delete('/{post}',[PostController::class , 'destroy'])->name('post.destroy');
        Route::get('/download/{attachment}',[PostController::class , 'downloadAttachment'])->name('post.download');
        Route::post('/{post}/reaction', [PostController::class, 'PostReaction'])
            ->name('post.reaction');
        Route::post('/{post}/comment', [PostController::class, 'CreateComment'])
                ->name('post.comment.create');
        Route::post('/api-post', [PostController::class, 'aiPostsUsingGemini'])
                ->name('post.aiPostsUsingGemini');
        });


    // update for group
    
    
    
    //     Route::get('/profile', [ProfileContgroup.profileroller::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update-iamges', [ProfileController::class, 'UpdateImages'])
         ->name('profile.updateimages');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/follow/{user}', [UserController::class, 'follow'])->name('user.follow');
    Route::get('/search/{keywords}', [SearchController::class, 'search'])->name('search');



        //comments
        Route::prefix('/comment')->group(function(){
           
            Route::delete('/{comment}', [PostController::class, 'DeleteComment'])
                ->name('comment.delate');
            Route::put('/{comment}', [PostController::class, 'updateComment'])
                ->name('comment.update');
            Route::post('/{comment}/reactions', [PostController::class, 'CommentReactions'])
                ->name('comment.CommentReactions');
        });
  
    }
    );

require __DIR__.'/auth.php';
