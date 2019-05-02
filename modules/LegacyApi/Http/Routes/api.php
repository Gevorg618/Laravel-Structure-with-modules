<?php

Route::group([
    'middleware' => 'api',
    'prefix' => 'api',
    'namespace' => 'LegacyApi\Http\Controllers'
], function () {

    Route::get('/', 'IndexController@index');
});