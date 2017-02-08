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
Route::middleware('api')->get('movies', 'MoviesController@list');
Route::middleware('api')->get('movies/{id}', 'MoviesController@read');
Route::middleware('api')->put('movies/{id}', 'MoviesController@update');
Route::middleware('api')->post('movies', 'MoviesController@create');
Route::middleware('api')->post('movies/thumbnail/{id}', 'MoviesController@thumbnail');

Route::middleware('api')->post('movies/{id}/cast', 'MoviesController@castAdd');
Route::middleware('api')->delete('movies/{id}/cast/{actorId}', 'MoviesController@castRemove');

/* actors */
Route::middleware('api')->get('actors', 'ActorsController@list');
Route::middleware('api')->get('actors/{id}', 'ActorsController@read');
Route::middleware('api')->put('actors/{id}', 'ActorsController@update');
Route::middleware('api')->post('actors', 'ActorsController@create');
Route::middleware('api')->post('actors/thumbnail/{id}', 'ActorsController@thumbnail');

/* genres */
Route::middleware('api')->get('genres', 'GenresController@list');
Route::middleware('api')->get('genres/{id}', 'GenresController@read');
Route::middleware('api')->put('genres/{id}', 'GenresController@update');
Route::middleware('api')->post('genres', 'GenresController@create');
Route::middleware('api')->delete('genres/{id}', 'GenresController@remove');