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
    Route::post('/season/{season}/invite', 'InvitationController@store');

    Route::patch('/baker/{baker}', 'SeasonBakersController@update');
    Route::patch('/episode/{episode}', 'SeasonEpisodeController@update');

    Route::post('/episode/{episode}/result', 'EpisodeResultsController@store');

    Route::get('/result', 'ResultsController@create');
    Route::Post('/result', 'ResultsController@store');

    Route::Post('/episode/{episode}/prediction', 'PredictionsController@store');
    Route::Post('/episode/{episode}/complete', 'PredictionsController@complete');

    Route::get('/home', 'HomeController@index')->name('home');
});


Auth::routes();
