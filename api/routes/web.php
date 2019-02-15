<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('admin', 'AdminController');

Route::prefix('api')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::delete('sessions', 'Auth\LoginController@logout');

        Route::namespace('Api')->group(function () {
            Route::get('sessions', 'SessionController@show');
            Route::post('posts/parse', 'PostController@parse');

            Route::apiResource('posts', 'PostController');
            Route::apiResource('posts.comments', 'PostCommentController')->only(['index', 'destroy']);
            Route::apiResource('pages', 'PageController');
            Route::apiResource('tags', 'TagController');
            Route::apiResource('uploads', 'UploadController');
            Route::apiResource('users', 'UserController');
            Route::apiResource('settings', 'SettingController');
        });
    });

    Route::post('sessions', 'Auth\LoginController@login');
});

Route::get('/', 'FrontController@index');

Route::get('archives/{year?}/{month?}/{day?}', 'FrontController@archives')
    ->where('year', '[0-9]+')
    ->where('month', '[0-9]+')
    ->where('day', '[0-9]+');

Route::get('tags', 'FrontController@tags');
Route::get('tags/{slug}', 'FrontController@tagArchive');

Route::get('rss', 'FrontController@rss');

Route::get('{path?}', 'FrontController@single')->where('path', '.*');

Route::post('posts/{post}/likes', 'LikeController');
Route::post('posts/{post}/comments', 'CommentController');
