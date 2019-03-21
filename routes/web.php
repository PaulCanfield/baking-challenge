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

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'SeasonController@index');

    Route::resource('season', 'SeasonController');

    Route::post('/season/{season}/baker', 'SeasonBakersController@store');
    Route::post('/season/{season}/episode', 'SeasonEpisodeController@store');

    Route::patch('/baker/{baker}', 'SeasonBakersController@update');
    Route::patch('/episode/{episode}', 'SeasonEpisodeController@update');

    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();
