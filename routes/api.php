<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;
use Whoops\Run;

//route get untuk menampilkan atau kirim data
//route post untuk input data
//route patch/put untuk update data
//route delete untuk hapus data
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, ('logout')]);
    Route::get('/me', [AuthenticationController::class, ('me')]); //me ialah untuk mendapatkan user siapayang login
    Route::post('/posts', [PostController::class, ('store')]); //store adalah standar penggunaan laravel untuk istilah create (menyimpan data)                                                        
    Route::patch('/posts/{id}', [PostController::class, ('update')])->middleware('pemilik-postingan'); //untuk membuat dan mendaftarkan middleware baru ada di middware kernel.php
    Route::delete('/posts/{id}', [PostController::class, ('destroy')])->middleware('pemilik-postingan'); //destroy adalah standar penggunaan laravel untuk istilah hapus data                                                                                                            //untuk membuat dan mendaftarkan middleware baru ada di middware kernel.php

    Route::post('/comment', [CommentController::class, ('store')]);
    Route::patch('/comment/{id}', [CommentController::class, ('update')])->middleware('pemilik-komentar');
    Route::delete('/comment/{id}', [CommentController::class, ('destroy')])->middleware('pemilik-komentar');
});

Route::get('/posts', [PostController::class, ('index')]);  //index adalah standar penggunaan untuk list atau menampilkan data
Route::get('/posts/{id}', [PostController::class, ('show')]); //detail ialah standar penggunaan laravel untuk show data (menampilkan detail data)


Route::post('/login', [AuthenticationController::class, ('login')]);
