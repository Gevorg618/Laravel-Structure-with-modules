<?php

// Authentication routes...
Route::get('login', ['as' => 'admin.login', 'uses' => 'Auth\AuthController@showLoginForm']);
Route::post('login', ['as' => 'admin.login', 'uses' => 'Auth\AuthController@login']);
Route::get('logout', ['as' => 'admin.logout', 'uses' => 'Auth\AuthController@logout']);