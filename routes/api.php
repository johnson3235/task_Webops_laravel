<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');


});




Route::controller(PostController::class)->group(function () {
    Route::get('/post/all', 'getallposts');
    Route::post('/post/add', 'AddPost');
    Route::put('/post/update/{id}', 'UpdatePost');
    Route::delete('/post/delete/{id}', 'DeletePost');
    

});


Route::controller(CommentController::class)->group(function () {
    Route::get('/posts/{postId}/comments', 'getallComments');
    Route::post('/comment/add', 'addComment');
    Route::put('/comment/update/{id}', 'updateComment');
    Route::delete('/comment/delete/{id}', 'DeleteComment');
    

});


