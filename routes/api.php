<?php

use Illuminate\Http\Request;

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

Route::namespace('Api\Auth')->prefix('auth')->group(function () {
    Route::get('login', 'LoginController@redirectToProvider');
    Route::get('callback', 'LoginController@handleProviderCallback');
});

Route::middleware('auth:api')->get('/spotify/playlists', 'Api\SpotifyController@playlists');
