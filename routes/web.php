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

Route::get('/u/{User:username}', [ProfileController::class, 'index'])->name('profile');

Route::middleware('auth')->group(function () {
    Route::post('/profile/update-iamges', [ProfileController::class, 'UpdateImages'])
         ->name('profile.updateimages');
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
    
    // group
    Route::post('/group', [GroupController::class, 'store'])
    ->name('group.create');
    }
    
    );

require __DIR__.'/auth.php';
