<?php

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});
Route::get('categories', 'CategoryController@index');
Route::post('/categories', 'CategoryController@store');
Route::get('/categories/{id}', 'CategoryController@show');
Route::put('/categories/{id}', 'CategoryController@update');
Route::delete('/categories/{id}', 'CategoryController@destroy');

Route::get('actors', 'ActorController@index');
Route::post('/actors', 'ActorController@store');
Route::get('/actors/{id}', 'ActorController@show');
Route::put('/actors/{id}', 'ActorController@update');
Route::delete('/actors/{id}', 'ActorController@destroy');

Route::get('directors', 'DirectorController@index');
Route::post('/directors', 'DirectorController@store');
Route::get('/directors/{id}', 'DirectorController@show');
Route::put('/directors/{id}', 'DirectorController@update');
Route::delete('/directors/{id}', 'DirectorController@destroy');

Route::get('movies', 'MovieController@index');
Route::post('/movies', 'MovieController@store');
Route::get('/movies/{id}', 'MovieController@show');
Route::put('/movies/{id}', 'MovieController@update');
Route::delete('/movies/{id}', 'MovieController@destroy');
Route::post('/movies/search', 'MovieController@search');
Route::post('/movies/comments', 'MovieController@comment');
Route::get('/movies/comments/{id}', 'MovieController@getComments');
