<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'MongoController@index');
Route::get('keywords', 'MongoController@keywords');

Route::group(['prefix' => 'exec'], function () {
    Route::get('stream', 'MongoController@stream');
    Route::get('mapreduce', 'MongoController@mapreduce');
});


Route::group(['prefix' => 'api'], function () {
    Route::get('twitter/total', 'ApiController@twitterTotal');
    Route::get('twitter/keywords', 'ApiController@keywords');

    Route::get('hashtag/total', 'ApiController@hashtagTotal');
    Route::get('hashtag/aggregate', 'ApiController@aggregateHashtag');
});

