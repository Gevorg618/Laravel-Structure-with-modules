<?php

Route::group(['prefix' => 'tiger', 'namespace' => 'Tiger'], function () {
    Route::group(['prefix' => 'clients'], function () {
        Route::get(
          '/',
          ['as' => 'admin.tiger.clients.index', 'uses' => 'ClientsController@index']
      );
        Route::get(
          'data',
          ['as' => 'admin.tiger.clients.data', 'uses' => 'ClientsController@data']
      );
        Route::get(
          'create',
          ['as' => 'admin.tiger.clients.create', 'uses' => 'ClientsController@create']
      );
        Route::post(
          'store',
          ['as' => 'admin.tiger.clients.store', 'uses' => 'ClientsController@store']
      );
        Route::get(
          '{id}/edit',
          ['as' => 'admin.tiger.clients.edit', 'uses' => 'ClientsController@edit']
      );
        Route::patch(
          '{id}',
          ['as' => 'admin.tiger.clients.update', 'uses' => 'ClientsController@update']
      );
    });

    Route::group(['prefix' => 'amc'], function () {
        Route::get(
        '/',
        ['as' => 'admin.tiger.amcs.index', 'uses' => 'AmcsController@index']
    );
        Route::get(
        'data',
        ['as' => 'admin.tiger.amcs.data', 'uses' => 'AmcsController@data']
    );
        Route::get(
        'create',
        ['as' => 'admin.tiger.amcs.create', 'uses' => 'AmcsController@create']
    );
        Route::post(
        'store',
        ['as' => 'admin.tiger.amcs.store', 'uses' => 'AmcsController@store']
    );
        Route::get(
        '{id}/edit',
        ['as' => 'admin.tiger.amcs.edit', 'uses' => 'AmcsController@edit']
    );
        Route::patch(
        '{id}',
        ['as' => 'admin.tiger.amcs.update', 'uses' => 'AmcsController@update']
    );
    });
});
