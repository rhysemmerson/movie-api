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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* /movies */
Route::middleware('api')->get('/movies', 'MoviesController@moviesList');
Route::middleware('api')->get('movies/{id}', 'ApiController@moviesRead');
Route::middleware('api')->put('movies/{id}', 'ApiController@moviesUpdate');
Route::middleware('api')->post('movies', 'ApiController@moviesCreate');
Route::middleware('api')->put('movies/thumbnail/{id}', 'ApiController@moviesUploadThumbnail');

/* actors */
Route::get('actors/{id}', 'ApiController@actorsRead');
Route::put('actors/{id}', 'ApiController@actorsUpdate');
Route::post('actors', 'ApiController@actorsCreate');
Route::put('actors/thumbnail/{id}', 'ApiController@actorsUploadThumbnail');

/* genres */
Route::get('genres/{id}', 'ApiController@genresRead');
Route::put('genres/{id}', 'ApiController@genresUpdate');
Route::post('genres', 'ApiController@genresCreate');