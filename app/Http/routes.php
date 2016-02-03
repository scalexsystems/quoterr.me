<?php

Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);
Route::get('/login', ['as' => 'login.default', 'uses' => 'Auth\AuthController@login']);
Route::get('/login/{provider}', ['as' => 'login', 'uses' => 'Auth\AuthController@redirectToProvider']);
Route::get('/login/{provider}/callback',
    ['as' => 'login.callback', 'uses' => 'Auth\AuthController@handleProviderCallback']);

Route::resource('quote', 'QuoteController');
Route::resource('tag', 'TagController');
Route::resource('author', 'AuthorController');
Route::get('search', ['as' => 'search', 'uses' => 'QuoteController@search']);

Route::get('/{quote?}', ['as' => 'home', 'uses' => 'WelcomeController@index']);

$api = Api::router();

$api->version('v1', ['middleware' => 'cors'], function ($api) {
    $api->get('quotes', ['as' => 'quotes', 'uses' => 'Quoterr\Http\Controllers\Api\ApiController@quotes']);
    $api->get('authors', ['as' => 'authors', 'uses' => 'Quoterr\Http\Controllers\Api\ApiController@authors']);
    $api->get('quote', ['as' => 'quote', 'uses' => 'Quoterr\Http\Controllers\Api\ApiController@quote']);
    $api->get('tag', ['as' => 'tag', 'uses' => 'Quoterr\Http\Controllers\Api\ApiController@tag']);
    $api->get('author', ['as' => 'author', 'uses' => 'Quoterr\Http\Controllers\Api\ApiController@author']);
});