<?php

Route::group([
    'middleware' => 'web',
    'as' => 'docs.',
    'domain' => config('legacyapi.docs.url'),
    'namespace' => 'LegacyApi\Http\Controllers\Docs'
], function () {

    Route::get('/', 'IndexController@index');
});