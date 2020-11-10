<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
        Route::get('/user', function (Request $request) {
            return resonse()->json($request->user());
        });
        Route::post('/logout', 'Auth\\LoginController@logout');

    });
    
});


