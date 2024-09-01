<?php

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
    Route::post('/post',[PostController::class , 'store'])->name('post.create');
    Route::put('/post/{post}', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{post}',[PostController::class , 'destroy'])->name('post.destroy');
    Route::get('/post/download/{attachment}',[PostController::class , 'downloadAttachment'])->name('post.download');
    Route::post('/post/{post}/reaction', [PostController::class, 'PostReaction'])
        ->name('post.reaction');
    Route::post('/post/{post}/comment', [PostController::class, 'CreateComment'])
        ->name('post.comment.create');
    Route::delete('/comment/{comment}', [PostController::class, 'DeleteComment'])
        ->name('post.comment.delate');
    Route::put('/comment/{comment}', [PostController::class, 'updateComment'])
        ->name('post.comment.update');
    
    
    }
    
    );

require __DIR__.'/auth.php';
