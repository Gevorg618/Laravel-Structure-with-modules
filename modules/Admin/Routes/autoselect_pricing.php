<?php

// AutoSelect & Pricing
Route::group(['prefix' => 'autoselect-pricing', 'namespace' => 'AutoSelectPricing',], function () {

  // Autoselect pricicng Versions
    Route::group(['prefix' => 'versions', 'namespace' => 'PricingVersion'], function () {
        Route::get('/', 'PricingVersionController@index')->name('admin.autoselect.pricing.versions.index');
        Route::get('/data', 'PricingVersionController@data')->name('admin.autoselect.pricing.versions.data');
        Route::get('/pricing-view', 'PricingVersionController@pricingView')->name('admin.autoselect.pricing.versions.pricing-view');
        Route::post('/pricing-store', 'PricingVersionController@pricingStore')->name('admin.autoselect.pricing.versions.pricing-store');
        Route::get('/pricing-edit/{id}', 'PricingVersionController@pricingEdit')->name('admin.autoselect.pricing.versions.pricing-edit');
        Route::post('/pricing-edit/{id}', 'PricingVersionController@pricingUpdate')->name('admin.autoselect.pricing.versions.pricing-update');
        Route::get('/pricing-clients/{id}', 'PricingVersionController@pricingClientsDownload')->name('admin.autoselect.pricing.versions.pricing-clients-download');
        Route::get('/pricing-download/{id}', 'PricingVersionController@pricingDownload')->name('admin.autoselect.pricing.versions.pricing-download');
        Route::get('/pricing-edit-addendas/{id}', 'PricingVersionController@pricingEditAddendas')->name('admin.autoselect.pricing.versions.pricing-edit-addendas');
        Route::post('/pricing-edit-addendas/{id}', 'PricingVersionController@pricingUpdateAddendas')->name('admin.autoselect.pricing.versions.pricing-update-addendas');
        Route::get('/pricing-view-by-state/{id}', 'PricingVersionController@pricingViewByState')->name('admin.autoselect.pricing.versions.pricing-view-by-state');
        Route::get('/pricing-view-by-one-state/{id}/{slug}', 'PricingVersionController@pricingViewByOneState')->name('admin.autoselect.pricing.versions.pricing-view-by-one-state');
        Route::post('/pricing-update-state/{id}/{slug}', 'PricingVersionController@pricingUpdateState')->name('admin.autoselect.pricing.versions.pricing-update-state');
        Route::get('/pricing-add-client/{id}', 'PricingVersionController@pricingAddClient')->name('admin.autoselect.pricing.versions.pricing-add-client');
        Route::post('/pricing-add-client/{id}', 'PricingVersionController@pricingStoreClient')->name('admin.autoselect.pricing.versions.pricing-store-client');
        Route::get('/pricing-custom-edit/{id}', 'PricingVersionController@pricingCustomEdit')->name('admin.autoselect.pricing.versions.pricing-custom-edit');
        Route::post('/pricing-custom-edit/{id}', 'PricingVersionController@pricingCustomUpdate')->name('admin.autoselect.pricing.versions.pricing-custom-update');
        Route::get('/pricing-client-download/{id}', 'PricingVersionController@pricingClientDownload')->name('admin.autoselect.pricing.versions.pricing-client-download');
        Route::get('/pricing-view-by-client/{id}', 'PricingVersionController@pricingViewByClient')->name('admin.autoselect.pricing.versions.pricing-view-by-client');
        Route::get('/pricing-view-by-one-client/{id}/{slug}', 'PricingVersionController@pricingViewByOneClient')->name('admin.autoselect.pricing.versions.pricing-view-by-one-client');
        Route::post('/pricing-update-client/{id}/{slug}', 'PricingVersionController@pricingUpdateClient')->name('admin.autoselect.pricing.versions.pricing-update-client');
        Route::get('/pricing-client-edit-addendas/{id}', 'PricingVersionController@pricingClientEditAddendas')->name('admin.autoselect.pricing.versions.pricing-client-edit-addendas');
        Route::post('/pricing-client-edit-addendas/{id}', 'PricingVersionController@pricingClientUpdateAddendas')->name('admin.autoselect.pricing.versions.pricing-client-update-addendas');
        Route::get('/pricing-client-delete/{id}', 'PricingVersionController@pricingClientDelete')->name('admin.autoselect.pricing.versions.pricing-client-delete');
        Route::get('/download-template', 'PricingVersionController@templateDownload')->name('admin.autoselect.pricing.versions.download-template');
        Route::post('/import-version', 'PricingVersionController@importVersion')->name('admin.autoselect.pricing.versions.import-version');
        Route::post('/import-client-version', 'PricingVersionController@importClientVersion')->name('admin.autoselect.pricing.versions.import-client-version');
    });

    // /AutoSelect Counties
    Route::group(['prefix' => 'counties'], function () {
        Route::get('/', ['as' => 'admin.autoselect.counties', 'uses' => 'AutoSelectCountiesController@index']);
        Route::get('data', ['as' => 'admin.autoselect.counties.data', 'uses' => 'AutoSelectCountiesController@data']);
        Route::get('/edit/{slug}', ['as' => 'admin.autoselect.counties.edit', 'uses' => 'AutoSelectCountiesController@edit']);
        Route::put('/{slug}/update', ['as' => 'admin.autoselect.counties.update', 'uses' => 'AutoSelectCountiesController@update']);
    });

    // Autoselect appraiser fees
    Route::group(['prefix' => 'appraiser-fees'], function () {
        Route::get('/', 'AutoSelectAppraiserFeesController@index')->name('admin.autoselect.appraiser.fees.index');
        Route::get('/download', 'AutoSelectAppraiserFeesController@downloadTemplate')->name('admin.autoselect.appraiser.fees.template.download');
        Route::get('/download/{state}', 'AutoSelectAppraiserFeesController@downloadStateTemplate')->name('admin.autoselect.appraiser.fees.template.state.download');
        Route::get('/show/{state}', 'AutoSelectAppraiserFeesController@show')->name('admin.autoselect.appraiser.fees.state.form');
        Route::post('/update/{state}', 'AutoSelectAppraiserFeesController@update')->name('admin.autoselect.appraiser.fees.store.form');
        Route::post('/store', 'AutoSelectAppraiserFeesController@store')->name('admin.autoselect.appraiser.fees.store');
    });

    // Autoselect turn times
    Route::group(['prefix' => 'turn-times'], function () {
        Route::get('/', 'AutoSelectTurnTimesController@index')->name('admin.autoselect.turn.times.index');
        Route::get('/create', 'AutoSelectTurnTimesController@create')->name('admin.autoselect.turn.times.create');
        Route::post('/store', 'AutoSelectTurnTimesController@store')->name('admin.autoselect.turn.times.store');
        Route::get('/edit/{slug}', 'AutoSelectTurnTimesController@edit')->name('admin.autoselect.turn.times.edit');
        Route::put('/edit/{slug}', 'AutoSelectTurnTimesController@update')->name('admin.autoselect.turn.times.update');
        Route::get('/delete/{id}', 'AutoSelectTurnTimesController@destroy')->name('admin.autoselect.turn.times.destroy');
    });

    // Autoselect Pricing Version Fees
    Route::group(['prefix' => 'pricing-fees'], function () {
        Route::get('/', 'AutoSelectPricingFees\BaseFeesController@index')->name('admin.autoselect.pricing.fees.index');

        Route::group(['prefix' => 'group'], function () {
            Route::post('/store', 'AutoSelectPricingFees\GroupFeesController@store')->name('admin.autoselect.pricing.group.fees.store');
            Route::get('/delete/{id}', 'AutoSelectPricingFees\GroupFeesController@destroy')->name('admin.autoselect.pricing.group.fees.destroy');
            Route::get('/states/{id}', 'AutoSelectPricingFees\GroupFeesController@states')->name('admin.autoselect.pricing.group.fees.states');
            Route::get('/state/{id}/{stateAbbr?}', 'AutoSelectPricingFees\GroupFeesController@state')->name('admin.autoselect.pricing.group.fees.state');
            Route::put('/state/{id}/{stateAbbr}', 'AutoSelectPricingFees\GroupFeesController@stateUpdate')->name('admin.autoselect.pricing.group.fees.update');
            Route::get('/download/{id}/{stateAbbr}', 'AutoSelectPricingFees\GroupFeesController@download')->name('admin.autoselect.pricing.group.fees.download');
            Route::post('/import-csv/{id}', 'AutoSelectPricingFees\GroupFeesController@import')->name('admin.autoselect.pricing.group.fees.import');
        });

        Route::group(['prefix' => 'version'], function () {
            Route::get('/state/{id}/{stateAbbr?}', 'AutoSelectPricingFees\VersionFeesController@state')->name('admin.autoselect.pricing.version.fees.state');
            Route::get('/states/{id}', 'AutoSelectPricingFees\VersionFeesController@states')->name('admin.autoselect.pricing.version.fees.states');
            Route::post('/import-csv/{id}', 'AutoSelectPricingFees\VersionFeesController@import')->name('admin.autoselect.pricing.version.fees.import');
            Route::put('/state/{id}/{stateAbbr}', 'AutoSelectPricingFees\VersionFeesController@stateUpdate')->name('admin.autoselect.pricing.version.fees.update');
            Route::get('/download/{id}/{stateAbbr}', 'AutoSelectPricingFees\VersionFeesController@download')->name('admin.autoselect.pricing.version.fees.download');
        });
    });
});
