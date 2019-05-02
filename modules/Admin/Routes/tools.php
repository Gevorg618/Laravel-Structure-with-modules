<?php

// Tools
Route::group(['prefix' => 'tools'], function () {

  // Settings
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', ['as' => 'admin.tools.settings', 'uses' => 'Tools\SettingsController@index']);

        Route::get(
          '/category/create',
          ['as' => 'admin.tools.settings.category.create', 'uses' => 'Tools\SettingsController@createCategory']
      );
        Route::post(
          '/category/create',
          ['as' => 'admin.tools.settings.category.create', 'uses' => 'Tools\SettingsController@createCategory']
      );
        Route::get(
          '/category/update/{id}',
          ['as' => 'admin.tools.settings.category.update', 'uses' => 'Tools\SettingsController@updateCategory']
      );
        Route::post(
          '/category/update/{id}',
          ['as' => 'admin.tools.settings.category.update', 'uses' => 'Tools\SettingsController@updateCategory']
      );

        Route::get(
          '/category/{id}',
          ['as' => 'admin.tools.settings.category.view', 'uses' => 'Tools\SettingsController@viewCategory']
      );
        Route::post(
          '/category/{id}',
          ['as' => 'admin.tools.settings.category.view', 'uses' => 'Tools\SettingsController@viewCategory']
      );
        Route::get('/category/data', [
          'as' => 'admin.tools.settings.category.data',
          'uses' => 'Tools\SettingsController@settingsCategoryData'
      ]);
    });

    //Logos
    Route::group(['prefix' => 'logos', 'namespace' => 'Tools'], function () {
        Route::get('/', ['as' => 'admin.tools.logos', 'uses' => 'LogoManagerController@index']);
        Route::get('/create', ['as' => 'admin.tools.logos.create', 'uses' => 'LogoManagerController@create']);
        Route::post('/store', ['as' => 'admin.tools.logos.store', 'uses' => 'LogoManagerController@store']);
        Route::get('/edit/{id}', ['as' => 'admin.tools.logos.edit', 'uses' => 'LogoManagerController@edit']);
        Route::put('/{id}/update', ['as' => 'admin.tools.logos.update', 'uses' => 'LogoManagerController@update']);
        Route::get('data', ['as' => 'admin.tools.logos.data', 'uses' => 'LogoManagerController@data']);
        Route::delete('/delete/{id}', ['as' => 'admin.tools.logos.delete', 'uses' => 'LogoManagerController@destroy']);
    });


    //Keys Legend
    Route::group(['prefix' => 'keys-legend', 'namespace' => 'Tools\KeysLegend'], function () {
        Route::get('/', ['as' => 'admin.tools.keys-legend', 'uses' => 'KeysLegendController@index']);
    });

    Route::group(['prefix' => 'templates'], function () {
        Route::get('/', ['as' => 'admin.tools.templates', 'uses' => 'Tools\TemplatesController@index']);
        Route::get(
          'data',
          ['as' => 'admin.tools.templates.data', 'uses' => 'Tools\TemplatesController@templatesData']
      );
        Route::any(
          'create/{id?}',
          ['as' => 'admin.tools.templates.create', 'uses' => 'Tools\TemplatesController@createTemplates']
      );
        Route::any(
          'update/{id}',
          ['as' => 'admin.tools.templates.update', 'uses' => 'Tools\TemplatesController@updateTemplates']
      );
        Route::get(
          'delete/{id}',
          ['as' => 'admin.tools.templates.delete', 'uses' => 'Tools\TemplatesController@deleteTemplates']
      );
    });


    Route::group(['prefix' => 'emails-sent', 'namespace' => 'Tools'], function () {
        Route::get('/', ['as' => 'admin.tools.emails-sent', 'uses' => 'EmailsSentController@index']);
        Route::get(
          '/data',
          ['as' => 'admin.tools.emails-sent.data', 'uses' => 'EmailsSentController@emailsSentData']
      );
        Route::post(
          '/emailBody',
          ['as' => 'admin.tools.emails-sent.email-body', 'uses' => 'EmailsSentController@getEmailBody']
      );
        Route::get(
          '/iframe/{id}',
          ['as' => 'admin.tools.emails-sent.iframe', 'uses' => 'EmailsSentController@loadIframe']
      );
    });

    Route::group(['prefix' => 'shipping-labels', 'namespace' => 'Tools'], function () {
        Route::get('/', ['as' => 'admin.tools.shipping-labels', 'uses' => 'ShippingLabelsController@index']);
        Route::get(
          '/data',
          ['as' => 'admin.tools.shipping-labels.data', 'uses' => 'ShippingLabelsController@shippingLabelsData']
      );
        Route::post(
          '/downloadPDF',
          ['as' => 'admin.tools.shipping-labels.downloadPDF', 'uses' => 'ShippingLabelsController@downloadPDF']
      );
    });

    // CustomPagesManagerController
    Route::group(['prefix' => 'custom-pages-manager', 'namespace' => 'Tools'], function () {
        Route::get('index', 'CustomPagesManagerController@index')->name('admin.tools.custom-pages-manager.index');
        Route::get('create', 'CustomPagesManagerController@create')->name('admin.tools.custom-pages-manager.create');
        Route::get('view/{id}', 'CustomPagesManagerController@showCustomPage')->name('admin.tools.custom-pages-manager.view');
        Route::post('store', 'CustomPagesManagerController@store')->name('admin.tools.custom-pages-manager.store');
        Route::get('data', 'CustomPagesManagerController@data')->name('admin.tools.custom-pages-manager.data');
        Route::get('delete/{id}', 'CustomPagesManagerController@delete')->name('admin.tools.custom-pages-manager.delete');
        Route::get('edit/{id}', 'CustomPagesManagerController@edit')->name('admin.tools.custom-pages-manager.edit');
        Route::put('edit/{id}', 'CustomPagesManagerController@update')->name('admin.tools.custom-pages-manager.update');
    });

    // GeoController
    Route::group(['prefix' => 'geo', 'namespace' => 'Tools'], function () {
        Route::get('index', 'GeoController@index')->name('admin.tools.geo.index');
        Route::get('create', 'GeoController@create')->name('admin.tools.geo.create');
        Route::post('store', 'GeoController@store')->name('admin.tools.geo.store');
        Route::get('data', 'GeoController@data')->name('admin.tools.geo.data');
        Route::get('edit/{id}', 'GeoController@edit')->name('admin.tools.geo.edit');
        Route::put('edit/{id}', 'GeoController@update')->name('admin.tools.geo.update');
        Route::get('delete/{id}', 'GeoController@delete')->name('admin.tools.geo.delete');
    });

    // UserOrderTransfersController
    Route::group(['prefix' => 'user-order-transfers', 'namespace' => 'Tools'], function () {
        Route::get('index', 'UserOrderTransfersController@index')->name('admin.tools.user-order-transfers.index');
        Route::get('load-info', 'UserOrderTransfersController@loadInfo')->name('admin.tools.user-order-transfers.load-info');
        Route::post('transfer-order', 'UserOrderTransfersController@transfer')->name('admin.tools.user-order-transfers.transfer-order');
        Route::get('transfered-orders/{id}', 'UserOrderTransfersController@transferedOrders')->name('admin.tools.user-order-transfers.transfered-orders');
        Route::get('orders', 'UserOrderTransfersController@ordersData')->name('admin.tools.user-order-transfers.orders');
        Route::get('search', 'UserOrderTransfersController@search')->name('admin.tools.user-order-transfers.search');
    });
});
