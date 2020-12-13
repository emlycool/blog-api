<?php

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
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
    Route::get('login/{provider}', 'Auth\\SocialAuthController@redirectToProvider');
    Route::get('login/{provider}/callback', 'Auth\\SocialAuthController@handleProviderCallback');

    Route::group([ 'middleware'=>['auth:sanctum'] ], function(){


        // Route::group(['prefix' => 'posts'], function(){
        //     Route::get('/', [PostController::class, 'index']);
        // });

        Route::get('/user', function (Request $request) {
            return response()->json(new UserResource($request->user()));
        });
        Route::post('/logout', 'Auth\\LoginController@logout');

    });
    // Route::group(['prefix' => 'posts'], function(){
    //     Route::get('/', [PostController::class, 'index']);
    //     Route::get('/{post}', [PostController::class, 'show']);
    // });
    Route::apiResource('posts', 'PostController');
    Route::get('blog/posts/{post:slug}', 'PostController@show');
    Route::post('posts/like/{post}', 'PostController@like');
    // Route::post('posts/un-like/{post}', 'PostController@unlike');
    Route::post('posts/{post}/comments', 'PostController@comment');
    Route::post('posts/{post}/comments/{comment}/reply', 'PostController@reply');

    Route::get('user/posts', 'PostController@userPosts');
    Route::get('user/posts/trashed', 'PostController@getTrashedPost');
    Route::put('user/posts/restore/{id}', 'PostController@restore');

    Route::apiResource('users', 'UserController');

    Route::apiResource('categories', 'CategoryController');


    Route::apiResource('tags', 'TagController');
});


