<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Auth\LoginController;

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



Route::group(['prefix'=> 'v1'], function(){
    
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', 'Auth\\RegisterController@register');
    Route::post('forgot-password/email', 'Auth\\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\\ResetPasswordController@reset');

    Route::group([ 'middleware'=>['auth:sanctum'] ], function(){


        // Route::group(['prefix' => 'posts'], function(){
        //     Route::get('/', [PostController::class, 'index']);
        // });

        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        });
        Route::post('/logout', 'Auth\\LoginController@logout');

    });
    // Route::group(['prefix' => 'posts'], function(){
    //     Route::get('/', [PostController::class, 'index']);
    //     Route::get('/{post}', [PostController::class, 'show']);
    // });
    Route::apiResource('posts', 'PostController');
    Route::get('blog/posts/{post:slug}', 'PostController@show');
    Route::get('user/posts', 'PostController@userPosts');
    Route::get('user/posts/trashed', 'PostController@getTrashedPost');
    Route::put('user/posts/restore/{id}', 'PostController@restore');

    Route::apiResource('users', 'UserController');

    Route::apiResource('categories', 'CategoryController');


    Route::apiResource('tags', 'TagController');
});


