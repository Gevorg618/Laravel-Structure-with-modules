<?php

Route::group([
    'middleware' => ['web', 'admin.auth'],
    'prefix' => 'admin',
    'namespace' => 'Modules\Admin\Http\Controllers'
], function () {
    Route::get('/', ['as' => 'admin', 'uses' => 'IndexController@index']);

    Route::get('/search', ['as' => 'admin.search', 'uses' => 'Search\SearchController@search']);
    Route::get('/mail/test/view', ['as' => 'admin.mail.test.view', 'uses' => 'Mail\TestController@index']);
    Route::get('/mail/test/send', ['as' => 'admin.mail.test.send', 'uses' => 'Mail\TestController@send']);


    // Authentication routes...
    Route::get('login', ['as' => 'admin.login', 'uses' => 'Auth\AuthController@showLoginForm']);
    Route::post('login', ['as' => 'admin.login', 'uses' => 'Auth\AuthController@login']);
    Route::get('logout', ['as' => 'admin.logout', 'uses' => 'Auth\AuthController@logout']);

    Route::group([
        'as' => 'admin.management.fha-licenses.',
        'prefix' => 'management/fha-licenses',
        'namespace' => 'Management'
    ], function () {
        Route::get('data', ['as' => 'data', 'uses' => 'FhaLicensesController@data']);
        Route::get('/', ['as' => 'index', 'uses' => 'FhaLicensesController@index']);
        Route::get('show/{id}', ['as' => 'show', 'uses' => 'FhaLicensesController@show']);
    });


    // AP Calendar
    Route::group(['prefix' => 'ap_calendar', 'namespace' => 'App'], function () {
        Route::get('/', 'APCalendarController@index')->name('admin.ap_calendar.index');
        Route::get('load', 'APCalendarController@load')->name('admin.ap_calendar.load');
    });

    // CRM
    Route::group(['prefix' => 'crm', 'namespace' => 'CRM', 'as' => 'admin.crm.'], function () {

        // Sale Stages
        Route::group(['prefix' => 'sale-stages', 'as' => 'sale.stages.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'SaleStagesController@index']);
            Route::any('create', ['as' => 'create', 'uses' => 'SaleStagesController@create']);
            Route::get('edit/{saleStage?}', ['as' => 'edit', 'uses' => 'SaleStagesController@edit']);
            Route::put('edit/{saleStage?}', ['as' => 'update', 'uses' => 'SaleStagesController@update']);
            Route::get('delete/{saleStage}', ['as' => 'delete', 'uses' => 'SaleStagesController@delete']);
            Route::get('data', ['as' => 'data', 'uses' => 'SaleStagesController@data']);
        });
    });

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


    // Manager Reports
    Route::group(['prefix' => 'manager-reports', 'namespace' => 'ManagerReports'], function () {

        // generator  Report
        Route::group(['prefix' => 'generator'], function () {
            Route::get('/', 'GeneratorController@index')->name('admin.reports.generator.index');
            Route::post('/download', 'GeneratorController@download')->name('admin.reports.generator.download');
            Route::get('search', 'GeneratorController@search')->name('admin.reports.generator.search');
            Route::post('/render-task', 'GeneratorController@renderTask')->name('admin.reports.generator.render.task');
            Route::post('/create-task', 'GeneratorController@createTask')->name('admin.reports.generator.create.task');
        });

        // User Report Generator
        Route::group(['prefix' => 'user-generator'], function () {
            Route::get('/', 'UserGeneratorController@index')->name('admin.reports.user.generator.index');
            Route::post('download', 'UserGeneratorController@download')->name('admin.reports.user.generator.download');
        });

        // Client Settings Report
        Route::group(['prefix' => 'client-settings'], function () {
            Route::get('/', 'ClientSettingsController@index')->name('admin.reports.client.setting.index');
            Route::post('/data', 'ClientSettingsController@data')->name('admin.reports.client.setting.data');
            Route::any('download', 'ClientSettingsController@download')->name('admin.reports.client.setting.download');
        });

        Route::group(['prefix' => 'docu-vault'], function () {
            Route::get('/', 'DocuVaultGeneratorController@index')->name('admin.reports.docu.vault.index');
            Route::post('/download', 'DocuVaultGeneratorController@download')->name('admin.reports.docu.vault.download');
        });

        //QC Report
        Route::group(['prefix' => 'qc-report'], function () {
            Route::get('/', ['as' => 'admin.manager-reports.qc-report', 'uses' => 'QCReportController@index']);
            Route::post('/form', ['as' => 'admin.manager-reports.qc-report.form', 'uses' => 'QCReportController@form']);
        });

        // Reconsideration Report
        Route::group(['prefix' => 'reconsideration'], function () {
            Route::get('/', 'ReconsiderationController@index')->name('admin.reports.reconsideration.index');
            Route::post('download', 'ReconsiderationController@download')->name('admin.reports.reconsideration.download');
        });

        // Tasks
        Route::group(['prefix' => 'tasks'], function () {
            Route::get('/', 'TasksController@index')->name('admin.reports.tasks.index');
            Route::get('/data', 'TasksController@data')->name('admin.reports.tasks.data');
            Route::get('/delete/{id}', 'TasksController@destroy')->name('admin.reports.tasks.destroy');
            Route::get('/edit/{id}', 'TasksController@edit')->name('admin.reports.tasks.edit');
            Route::post('/update/{id}', 'TasksController@update')->name('admin.reports.tasks.update');
            Route::post('/render-task/{id}', 'TasksController@renderTask')->name('admin.reports.tasks.render.task');
        });
    });


    //Integrations
    Route::group(['prefix' => 'integrations', 'namespace' => 'Integrations',], function () {

        //API Users
        Route::group(['prefix' => 'api-users', 'namespace' => 'APIUsers'], function () {
            Route::get('/', ['as' => 'admin.integrations.api-users', 'uses' => 'APIUsersController@index']);
            Route::get('data', ['as' => 'admin.integrations.api-users.data', 'uses' => 'APIUsersController@data']);
            Route::get('/logs/{id}', ['as' => 'admin.integrations.api-users.logs', 'uses' => 'APIUsersController@logs']);
            Route::get('/search-log', ['as' => 'admin.integrations.api-users.search', 'uses' => 'APIUsersController@search']);
            Route::get('/log-content/{id}', ['as' => 'admin.integrations.api-users.content', 'uses' => 'APIUsersController@logsContent']);
            Route::get('/create', ['as' => 'admin.integrations.api-users.create', 'uses' => 'APIUsersController@create']);
            Route::post('/create', ['as' => 'admin.integrations.api-users.store', 'uses' => 'APIUsersController@store']);
            Route::get('/edit/{id}', ['as' => 'admin.integrations.api-users.edit', 'uses' => 'APIUsersController@edit']);
            Route::put('/update/{id}', ['as' => 'admin.integrations.api-users.update', 'uses' => 'APIUsersController@update']);
        });

        //FNC
        Route::group(['prefix' => 'fnc', 'namespace' => 'FNC',], function () {
            Route::get('/', ['as' => 'admin.integrations.fnc', 'uses' => 'FNCController@index']);
            Route::post('/update-fnc-statuses', ['as' => 'admin.integrations.update-fnc-statuses', 'uses' => 'FNCController@updateStatuses']);
            Route::post('/update-fnc-loan-reason', ['as' => 'admin.integrations.update-fnc-loan-reason', 'uses' => 'FNCController@updateLoanReason']);
            Route::post('/update-fnc-loan-type', ['as' => 'admin.integrations.update-fnc-loan-type', 'uses' => 'FNCController@updateLoanType']);
            Route::post('/update-fnc-appr-types', ['as' => 'admin.integrations.update-fnc-appr-types', 'uses' => 'FNCController@updateApprTypes']);
            Route::post('/update-fnc-property-types', ['as' => 'admin.integrations.update-fnc-property-types', 'uses' => 'FNCController@updatePropertyTypes']);
        });

        //MercuryNetwork
        Route::group(['prefix' => 'mercury', 'namespace' => 'MercuryNetwork',], function () {
            Route::get('/', ['as' => 'admin.integrations.mercury', 'uses' => 'MercuryNetworkController@index']);
            Route::post('/update-statuses', ['as' => 'admin.integrations.update-statuses', 'uses' => 'MercuryNetworkController@updateStatuses']);
            Route::post('/update-loan-reason', ['as' => 'admin.integrations.update-loan-reason', 'uses' => 'MercuryNetworkController@updateLoanReason']);
            Route::post('/update-loan-type', ['as' => 'admin.integrations.update-loan-type', 'uses' => 'MercuryNetworkController@updateLoanType']);
            Route::post('/update-appr-types', ['as' => 'admin.integrations.update-appr-types', 'uses' => 'MercuryNetworkController@updateApprTypes']);
            Route::get('/equity-edge-create', ['as' => 'admin.integrations.equity-edge-create', 'uses' => 'MercuryNetworkController@equityEdgeCreate']);
            Route::post('/equity-edge-store', ['as' => 'admin.integrations.equity-edge-store', 'uses' => 'MercuryNetworkController@equityEdgeStore']);
            Route::get('/equity-edge-destroy/{id}', ['as' => 'admin.integrations.equity-edge-destroy', 'uses' => 'MercuryNetworkController@equityEdgeDestroy']);
            Route::get('/equity-edge-edit/{id}', ['as' => 'admin.integrations.equity-edge-edit', 'uses' => 'MercuryNetworkController@equityEdgeEdit']);
            Route::post('/equity-edge-update/{id}', ['as' => 'admin.integrations.equity-edge-update', 'uses' => 'MercuryNetworkController@equityEdgeUpdate']);
        });

        // Google API
        Route::group(['prefix' => 'google', 'namespace' => 'Google',], function () {
            Route::get('/', ['as' => 'admin.integrations.google', 'uses' => 'GoogleAPIController@index']);
            Route::get('/search_email', ['as' => 'admin.integrations.google.search_email', 'uses' => 'GoogleAPIController@searchEmail']);
            Route::get('/view_email_message', ['as' => 'admin.integrations.google.view_email_message', 'uses' => 'GoogleAPIController@viewEmailMessage']);
            Route::get('/oauth_callback', ['as' => 'admin.integrations.google.oauth_callback', 'uses' => 'GoogleAPIController@oauthCallback']);
            Route::get('/revoke', ['as' => 'admin.integrations.google.revoke', 'uses' => 'GoogleAPIController@revoke']);
            Route::get('/refresh', ['as' => 'admin.integrations.google.refresh', 'uses' => 'GoogleAPIController@refresh']);
        });

        // Ditech manager
        Route::group(['prefix' => 'ditech', 'namespace' => 'Ditech'], function () {
            Route::get('/', 'DitechController@index')->name('admin.reports.ditech.index');
            Route::post('/download', 'DitechController@download')->name('admin.reports.ditech.download');
        });
    });

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

    Route::group(['prefix' => 'frontend-site', 'namespace' => 'FrontEnd', 'as' => 'admin.frontend-site.'], function () {

        Route::group(['prefix' => 'header-carousel', 'as' => 'header-carousel.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'HeaderCarouselController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'HeaderCarouselController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'HeaderCarouselController@create']);
            Route::get('/edit/{carousel?}', ['as' => 'edit', 'uses' => 'HeaderCarouselController@edit']);
            Route::put('/update/{carousel?}', ['as' => 'update', 'uses' => 'HeaderCarouselController@update']);
            Route::get('/delete/{carousel}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'HeaderCarouselController@destroy']);
        });

        Route::group(['prefix' => 'latest-news', 'as' => 'latest-news.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'LatestNewsController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'LatestNewsController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'LatestNewsController@create']);
            Route::any('/edit/{latestNews?}', ['as' => 'edit', 'uses' => 'LatestNewsController@edit']);
            Route::put('/update/{latestNews}', ['as' => 'update', 'uses' => 'LatestNewsController@update']);
            Route::get('/delete/{latestNews}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'LatestNewsController@destroy']);
        });
        Route::group(['prefix' => 'client-testimonials', 'as' => 'client-testimonials.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ClientTestimonialController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'ClientTestimonialController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'ClientTestimonialController@create']);
            Route::any('/edit/{testimonial?}', ['as' => 'edit', 'uses' => 'ClientTestimonialController@edit']);
            Route::put('/update/{testimonial}', ['as' => 'update', 'uses' => 'ClientTestimonialController@update']);
            Route::get('/delete/{testimonial}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'ClientTestimonialController@destroy']);
        });

        Route::group(['prefix' => 'team-member', 'as' => 'team-member.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'TeamMemberController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'TeamMemberController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'TeamMemberController@create']);
            Route::any('/edit/{member?}', ['as' => 'edit', 'uses' => 'TeamMemberController@edit']);
            Route::put('/update/{member}', ['as' => 'update', 'uses' => 'TeamMemberController@update']);
            Route::get('/delete/{member}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'TeamMemberController@destroy']);
        });

        Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ServicesController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'ServicesController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'ServicesController@create']);
            Route::any('/edit/{serviceProvide?}', ['as' => 'edit', 'uses' => 'ServicesController@create']);
            Route::put('/update/{serviceProvide}', ['as' => 'update', 'uses' => 'ServicesController@update']);
            Route::get('/delete/{serviceProvide}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'ServicesController@destroy']);
        });

        Route::group(['prefix' => 'custom-pages'], function () {
            Route::get('/', ['as' => 'admin.front-end.custom-pages', 'uses' => 'CustomPagesController@index']);
        });

        Route::group(['prefix' => 'stats', 'as' => 'stats.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'StatsController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'StatsController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'StatsController@create']);
            Route::any('/edit/{stat?}', ['as' => 'edit', 'uses' => 'StatsController@create']);
            Route::put('/update/{stat}', ['as' => 'update', 'uses' => 'StatsController@update']);
            Route::get('/delete/{stat}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'StatsController@destroy']);

        });

        Route::group(['prefix' => 'navigation-menu', 'as' => 'navigation-menu.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'NavigationController@index']);
            Route::get('/data', ['as' => 'data', 'uses' => 'NavigationController@data']);
            Route::any('/create', ['as' => 'create', 'uses' => 'NavigationController@create']);
            Route::any('/edit/{navigationMenu?}', ['as' => 'edit', 'uses' => 'NavigationController@create']);
            Route::put('/update/{navigationMenu}', ['as' => 'update', 'uses' => 'NavigationController@update']);
            Route::get('/delete/{navigationMenu}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'NavigationController@destroy']);

        });
    });

    //Statistics
    Route::group(['prefix' => 'statistics-user-tracking', 'namespace' => 'Statistics'], function () {

        //  StatisticsController
        Route::group(['prefix' => 'statistics'], function () {
            Route::get('index', 'StatisticsController@index')->name('admin.statistics.index');
            Route::get('show', 'StatisticsController@show')->name('admin.statistics.show');
            Route::get('calendar-data', 'StatisticsController@calendarData')->name('admin.statistics.calendar-data');
        });

        //  BigStatisticsController
        Route::group(['prefix' => 'big-statistics'], function () {
            Route::get('index', 'BigStatisticsController@index')->name('admin.statistics.big.index');
            Route::get('show', 'BigStatisticsController@show')->name('admin.statistics.big.show');
        });

        // AccountingBigStatisticsController
        Route::group(['prefix' => 'accounting-big-statistics'], function () {
            Route::get('index', 'AccountingBigStatisticsController@index')->name('admin.statistics.accounting-big.index');
            Route::get('show', 'AccountingBigStatisticsController@show')->name('admin.statistics.accounting-big.show');
        });

        // Average Delay Codes
        Route::group(['prefix' => 'avg-delay-codes'], function () {
            Route::get('/', ['as' => 'admin.statistics.avg-delay-codes', 'uses' => 'AvgDelayCodesController@index']);
            Route::post('/', ['as' => 'admin.statistics.avg-delay-codes', 'uses' => 'AvgDelayCodesController@submit']);
        });

        //  DashboardStatisticsController
        Route::group(['prefix' => 'dashborad-statistics'], function () {
            Route::get('index', 'DashboardStatisticsController@index')->name('admin.statistics.dashboard.index');
            Route::get('show', 'DashboardStatisticsController@show')->name('admin.statistics.dashboard.show');
        });

        //  SalesCommissionReportsController
        Route::group(['prefix' => 'sales-commission'], function () {
            Route::get('index', 'SalesCommissionReportsController@index')->name('admin.statistics.sales-commission.index');
            Route::get('show', 'SalesCommissionReportsController@show')->name('admin.statistics.sales-commission.show');
        });

        //  StatusSelectController
        Route::group(['prefix' => 'status-select'], function () {
            Route::get('index', 'StatusSelectController@index')->name('admin.statistics.status-select.index');
            Route::get('show', 'StatusSelectController@show')->name('admin.statistics.status-select.show');
            Route::get('details/{slug}', 'StatusSelectController@details')->name('admin.statistics.status-select.details');
        });

        // System Statistics
        Route::group(['prefix' => 'system-statistics'], function () {
            Route::get('/', ['as' => 'admin.statistics.system-statistics', 'uses' => 'SystemStatisticsController@index']);
        });

        // User Logins
        Route::group(['prefix' => 'user-logins'], function () {
            Route::get('/', ['as' => 'admin.statistics.user.logins', 'uses' => 'UserLoginsController@index']);
            Route::get('/data', ['as' => 'admin.statistics.user.logins.data', 'uses' => 'UserLoginsController@userLoginsData']);
        });

        // User Logs
        Route::group(['prefix' => 'user-logs'], function () {
            Route::get('/', ['as' => 'admin.statistics.user-logs', 'uses' => 'UserLogsController@index']);
            Route::get('/data', ['as' => 'admin.statistics.user-logs.data', 'uses' => 'UserLogsController@userLogsData']);
            Route::post('/htmlContent/', ['as' => 'admin.statistics.user-logs.html-content', 'uses' => 'UserLogsController@getHtmlContent']);
            Route::get('/iframe/{id}', ['as' => 'admin.statistics.user-logs.iframe', 'uses' => 'UserLogsController@loadIframe']);
        });
    });

    // Customizations
    Route::group(['prefix' => 'customizations', 'namespace' => 'Customizations'], function () {

        // Access Type
        Route::group(['as' => 'admin.appraisal.access_type.', 'prefix' => 'access-type'], function () {
            Route::get('data', ['as' => 'data', 'uses' => 'AccessTypeController@getData']);
            Route::get('/', ['as' => 'index', 'uses' => 'AccessTypeController@index']);
            Route::get('create', ['as' => 'create', 'uses' => 'AccessTypeController@create']);
            Route::post('store', ['as' => 'store', 'uses' => 'AccessTypeController@store']);
            Route::get('show/{id}', ['as' => 'show', 'uses' => 'AccessTypeController@show']);
            Route::get('edit/{id}', ['as' => 'edit', 'uses' => 'AccessTypeController@edit']);
            Route::post('edit/{id}', ['as' => 'update', 'uses' => 'AccessTypeController@update']);
            Route::get('delete/{id}', ['as' => 'delete', 'uses' => 'AccessTypeController@destroy']);
        });

        // loan Reason
        Route::group(['prefix' => 'loanreason'], function () {
            Route::get('/', ['as' => 'admin.appraisal.loanreason', 'uses' => 'LoanReasonController@index']);
            Route::get('data', ['as' => 'admin.appraisal.loanreason.data', 'uses' => 'LoanReasonController@loanreasonData']);
            Route::any('create/{loanreason?}', ['as' => 'admin.appraisal.loanreason.create', 'uses' => 'LoanReasonController@createLoanReason']);
            Route::put('update/{loanreason}', ['as' => 'admin.appraisal.loanreason.update', 'uses' => 'LoanReasonController@updateLoanReason']);
            Route::get('delete/{loanreason}', ['as' => 'admin.appraisal.loanreason.delete', 'uses' => 'LoanReasonController@deleteLoanReason']);
        });

        //  Loan Type
        Route::group(['prefix' => 'loantype'], function () {
            Route::get('/', ['as' => 'admin.appraisal.loantype', 'uses' => 'LoanTypeController@index']);
            Route::get('data', ['as' => 'admin.appraisal.loantype.data', 'uses' => 'LoanTypeController@loanTypeData']);
            Route::any('create/{id?}', ['as' => 'admin.appraisal.loantype.create', 'uses' => 'LoanTypeController@createLoanType']);
            Route::any('update/{id}', ['as' => 'admin.appraisal.loantype.update', 'uses' => 'LoanTypeController@updateLoanType']);
            Route::get('delete/{id}', ['as' => 'admin.appraisal.loantype.delete', 'uses' => 'LoanTypeController@deleteLoanType']);
        });

        // Occupancy Statuses
        Route::group(['prefix' => 'occupancy'], function () {
            Route::get('/', ['as' => 'admin.appraisal.occupancy.status', 'uses' => 'OccupancyStatusController@index']);
            Route::get('data', ['as' => 'admin.appraisal.occupancy.data', 'uses' => 'OccupancyStatusController@occupancyData']);
            Route::any('create/{occupancy?}', ['as' => 'admin.appraisal.occupancy.create', 'uses' => 'OccupancyStatusController@createOccupancy']);
            Route::put('update/{occupancy}', ['as' => 'admin.appraisal.occupancy.update', 'uses' => 'OccupancyStatusController@updateOccupancy']);
            Route::get('delete/{occupancy}', ['as' => 'admin.appraisal.occupancy.delete', 'uses' => 'OccupancyStatusController@deleteOccupancy']);
        });

        // Property Types
        Route::group(['prefix' => 'property-types'], function () {
            Route::get('/', ['as' => 'admin.appraisal.property-types.index', 'uses' => 'PropertyTypesController@index']);
            Route::get('data', ['as' => 'admin.appraisal.property-types.data', 'uses' => 'PropertyTypesController@data']);
            Route::get('create', ['as' => 'admin.appraisal.property-types.create', 'uses' => 'PropertyTypesController@create']);
            Route::post('store', ['as' => 'admin.appraisal.property-types.store', 'uses' => 'PropertyTypesController@store']);
            Route::get('{id}/edit', ['as' => 'admin.appraisal.property-types.edit', 'uses' => 'PropertyTypesController@edit']);
            Route::patch('{id}', ['as' => 'admin.appraisal.property-types.update', 'uses' => 'PropertyTypesController@update']);
            Route::get('{propertyType}', ['as' => 'admin.appraisal.property-types.delete', 'uses' => 'PropertyTypesController@delete']);
        });

        // Addenda
        Route::group(['prefix' => 'addendas'], function () {
            Route::get('/', ['as' => 'admin.appraisal.addendas', 'uses' => 'AddendaController@index']);
            Route::get('data', ['as' => 'admin.appraisal.addendas.data', 'uses' => 'AddendaController@addendasData']);
            Route::any('create/{addenda?}', ['as' => 'admin.appraisal.addendas.create', 'uses' => 'AddendaController@createAddenda']);
            Route::put('update/{addenda}', ['as' => 'admin.appraisal.addendas.update', 'uses' => 'AddendaController@updateAddenda']);
            Route::get('delete/{addenda}', ['as' => 'admin.appraisal.addendas.delete', 'uses' => 'AddendaController@deleteAddenda']);
        });

        // DocuVault Appraisal Types
        Route::group(['prefix' => 'appraisal'], function () {
            Route::get('/', ['as' => 'admin.docuvault.appraisal.index', 'uses' => 'AppraisalController@index']);
            Route::get('data', ['as' => 'admin.docuvault.appraisal.data', 'uses' => 'AppraisalController@data']);
            Route::get('create', ['as' => 'admin.docuvault.appraisal.create', 'uses' => 'AppraisalController@create']);
            Route::post('store', ['as' => 'admin.docuvault.appraisal.store', 'uses' => 'AppraisalController@store']);
            Route::get('{id}/edit', ['as' => 'admin.docuvault.appraisal.edit', 'uses' => 'AppraisalController@edit']);
            Route::patch('{id}', ['as' => 'admin.docuvault.appraisal.update', 'uses' => 'AppraisalController@update']);
            Route::get('{appraisal}', ['as' => 'admin.docuvault.appraisal.delete', 'uses' => 'AppraisalController@delete']);
        });

        // Valuation Order Statuses
        Route::group(['prefix' => 'valuation'], function () {
            Route::get('/', ['as' => 'admin.valuation.orders.status', 'uses' => 'ValuationOrderStatusController@index']);
            Route::get('data', ['as' => 'admin.valuation.orders.status.data', 'uses' => 'ValuationOrderStatusController@orderStatusData']);
            Route::any('create/{status?}', ['as' => 'admin.valuation.orders.status.create', 'uses' => 'ValuationOrderStatusController@createOrderStatus']);
            Route::put('update/{status}', ['as' => 'admin.valuation.orders.status.update', 'uses' => 'ValuationOrderStatusController@updateOrderStatus']);
            Route::get('delete/{status}', ['as' => 'admin.valuation.orders.status.delete', 'uses' => 'ValuationOrderStatusController@deleteOrderStatus']);
        });

        // Delay Codes
        Route::group(['prefix' => 'delay-codes'], function () {
            Route::get('/', ['as' => 'admin.appraisal.delay-codes', 'uses' => 'DelayCodesController@index']);
            Route::get('data', ['as' => 'admin.appraisal.delay-codes.data', 'uses' => 'DelayCodesController@delayCodesData']);
            Route::any('create/{delayCode?}', ['as' => 'admin.appraisal.delay-codes.create', 'uses' => 'DelayCodesController@createDelayCodes']);
            Route::put('update/{delayCode}', ['as' => 'admin.appraisal.delay-codes.update', 'uses' => 'DelayCodesController@updateDelayCodes']);
            Route::get('delete/{delayCode}', ['as' => 'admin.appraisal.delay-codes.delete', 'uses' => 'DelayCodesController@deleteDelayCodes']);
        });

        // Sale Taxes
        Route::group(['prefix' => 'sale-tax', 'as' => 'admin.management.sale.tax.'], function () {
            Route::get('/', ['as' => 'index', 'uses' => 'SaleTaxController@index']);
            Route::get('data', ['as' => 'data', 'uses' => 'SaleTaxController@data']);
            Route::get('edit/{state}', ['as' => 'edit', 'uses' => 'SaleTaxController@edit']);
            Route::put('edit', ['as' => 'update', 'uses' => 'SaleTaxController@update']);
            Route::get('create/{saleTax?}', ['as' => 'create', 'uses' => 'SaleTaxController@create']);
            Route::post('create/{saleTax?}', ['as' => 'createPost', 'uses' => 'SaleTaxController@update']);
            Route::get('counties', ['as' => 'counties', 'uses' => 'SaleTaxController@counties']);
        });

        // AMC Registrations
        Route::group(['prefix' => 'amc-licenses'], function () {
            Route::get('/', ['as' => 'admin.management.amc-licenses', 'uses' => 'AMCLicensesController@index']);
            Route::get('data', ['as' => 'admin.management.amc-licenses.data', 'uses' => 'AMCLicensesController@amcLicensesData']);
            Route::any('create/{AMCLicense?}', ['as' => 'admin.management.amc-licenses.create', 'uses' => 'AMCLicensesController@createAMCLicense']);
            Route::put('update/{AMCLicense}', ['as' => 'admin.management.amc-licenses.update', 'uses' => 'AMCLicensesController@updateAMCLicense']);
            Route::get('delete/{AMCLicense}', ['as' => 'admin.management.amc-licenses.delete', 'uses' => 'AMCLicensesController@deleteAMCLicense']);
        });

        // Client Bulk Change Tool
        Route::group(['prefix' => 'client-bulk-tool'], function () {
            Route::get('/', ['as' => 'admin.management.client-bulk-tool', 'uses' => 'ClientBulkChangeToolController@index']);
            Route::post('/update', ['as' => 'admin.management.client-bulk-tool.update', 'uses' => 'ClientBulkChangeToolController@update']);
        });

        //Turn Time by State
        Route::group(['prefix' => 'turn-time-by-state'], function () {
            Route::get('/', ['as' => 'admin.management.turn-time-by-state', 'uses' => 'TurnTimeByStateController@index']);
            Route::post('/save', ['as' => 'admin.management.turn-time-by-state.save', 'uses' => 'TurnTimeByStateController@save']);
        });

        // Aprrasial types
        Route::group(['prefix' => 'appr-types'], function () {
            Route::get('/index', 'TypesController@index')->name('admin.appraisal.appr-types.index');
            Route::get('/data', 'TypesController@data')->name('admin.appraisal.appr-types.data');
            Route::get('/create', 'TypesController@create')->name('admin.appraisal.appr-types.create');
            Route::post('/store', 'TypesController@store')->name('admin.appraisal.appr-types.store');
            Route::get('/edit/{id}', 'TypesController@edit')->name('admin.appraisal.appr-types.edit');
            Route::put('/update/{id}', 'TypesController@update')->name('admin.appraisal.appr-types.update');
        });

        // Aprrasial Order Statuses
        Route::group(['prefix' => 'appr-statuses'], function () {
            Route::get('/index', 'StatusController@index')->name('admin.appraisal.appr-statuses.index');
            Route::get('/data', 'StatusController@data')->name('admin.appraisal.appr-statuses.data');
            Route::get('/create', 'StatusController@create')->name('admin.appraisal.appr-statuses.create');
            Route::post('/store', 'StatusController@store')->name('admin.appraisal.appr-statuses.store');
            Route::get('/edit/{id}', 'StatusController@edit')->name('admin.appraisal.appr-statuses.edit');
            Route::put('/update/{id}', 'StatusController@update')->name('admin.appraisal.appr-statuses.update');
        });
    });

    // Appraisal Pipeline
    Route::group(['prefix' => 'appraisal-pipeline', 'namespace' => 'AppraisalPipeline'], function () {

        // Escalated Orders Pipeline
        Route::group(['prefix' => 'escalated-orders'], function () {
            Route::get('/', ['as' => 'admin.appraisal-pipeline.escalated-orders', 'uses' => 'EscalatedOrdersPipelineController@index']);
            Route::post('/filter-data', ['as' => 'admin.appraisal-pipeline.escalated-orders.filter-data', 'uses' => 'EscalatedOrdersPipelineController@filterData']);
            Route::post('/update-data', ['as' => 'admin.appraisal-pipeline.escalated-orders.update-data', 'uses' => 'EscalatedOrdersPipelineController@updateData']);
        });

        // Delayed Pipeline
        Route::group(['prefix' => 'delayed-pipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal-pipeline.delayed-pipeline', 'uses' => 'DelayedOrdersPipelineController@index']);
            Route::get('data', ['as' => 'admin.appraisal-pipeline.delayed-pipeline.data', 'uses' => 'DelayedOrdersPipelineController@data']);
            Route::delete('/delete/{id}', ['as' => 'admin.appraisal-pipeline.delayed-pipeline.delete', 'uses' => 'DelayedOrdersPipelineController@destroy']);
        });

        // Purchase & New Construction Pipeline
        Route::group(['prefix' => 'purchase-pipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal-pipeline.purchase-pipeline', 'uses' => 'PurchasePipelineController@index']);
            Route::post('/data', ['as' => 'admin.appraisal-pipeline.purchase-pipeline.data', 'uses' => 'PurchasePipelineController@data']);
            Route::post('/mark-as-reviewed', ['as' => 'admin.appraisal-pipeline.purchase-pipeline.mark-as-reviewed', 'uses' => 'PurchasePipelineController@markAsReviewed']);
            Route::post('/mark-as-worked', ['as' => 'admin.appraisal-pipeline.purchase-pipeline.mark-as-worked', 'uses' => 'PurchasePipelineController@markAsWorked']);
            Route::post('/mark-as-requested', ['as' => 'admin.appraisal-pipeline.purchase-pipeline.mark-as-requested', 'uses' => 'PurchasePipelineController@markAsRequested']);
        });

        // Unassigned Pipeline
        Route::group(['prefix' => 'unassigned-pipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal-pipeline.unassigned-pipeline', 'uses' => 'UnassignedPipelineController@index']);
            Route::post('/data', ['as' => 'admin.appraisal-pipeline.unassigned-pipeline.data', 'uses' => 'UnassignedPipelineController@data']);
            Route::post('/mark-as-reviewed', ['as' => 'admin.appraisal-pipeline.unassigned-pipeline.mark-as-reviewed', 'uses' => 'UnassignedPipelineController@markAsReviewed']);
            Route::post('/mark-priority', ['as' => 'admin.appraisal-pipeline.unassigned-pipeline.mark-priority', 'uses' => 'UnassignedPipelineController@markPriority']);
        });
    });

    //  Post Completion Pipelines
    Route::group(['prefix' => 'post-completion-pipelines', 'namespace' => 'PostCompletionPipelines'], function () {

        // Approve U/W Appraisals
        Route::group(['prefix' => 'appr-uw-pipeline'], function () {
            Route::get('/', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline', 'uses' => 'ApprUwPipelineController@index']);
            Route::get('/statistics', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.statistics', 'uses' => 'ApprUwPipelineController@statistics']);
            Route::post('/statistics', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.statistics', 'uses' => 'ApprUwPipelineController@getStatistics']);
            Route::post('/get-user-info', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.get-user-info', 'uses' => 'ApprUwPipelineController@getUserInfo']);
            Route::post('/get-user-condition-info', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.get-user-condition-info', 'uses' => 'ApprUwPipelineController@getUserConditionInfo']);
            Route::get('/uw-report', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.uw-report', 'uses' => 'ApprUwPipelineController@uwReport']);
            Route::post('/uw-report-download', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.uw-report-download', 'uses' => 'ApprUwPipelineController@uwReportDownload']);
            Route::get('/awaiting-approval-data', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.awaiting-approval-data', 'uses' => 'ApprUwPipelineController@dataAwaitingApproval']);
            Route::get('/pending-corrections-data', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.pending-corrections-data', 'uses' => 'ApprUwPipelineController@dataPendingCorrections']);
            Route::get('/uw-conditions/{id}', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.uw-conditions', 'uses' => 'ApprUwPipelineController@uwConditions']);
            Route::post('/save-conditions/{id}', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.save-conditions', 'uses' => 'ApprUwPipelineController@saveConditions']);
            Route::delete('/remove-all-conditions/{id}', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.remove-all-conditions', 'uses' => 'ApprUwPipelineController@destroyConditions']);


            Route::post('/save-pipeline/{id}', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.save-pipeline', 'uses' => 'ApprUwPipelineController@storePipeline']);
            Route::post('/check-real-view-submit', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.check-real-view-submit', 'uses' => 'ApprUwPipelineController@checkRealViwSubmit']);

            Route::get('/view-checklist/{id}', ['as' => 'admin.post-completion-pipelines.appr-uw-pipeline.view-checklist', 'uses' => 'ApprUwPipelineController@viewChecklist']);
        });

        // Reconsideration Pipeline
        Route::group(['prefix' => 'review-pipeline'], function () {
            Route::get('/', ['as' => 'admin.post-completion-pipelines.review-pipeline', 'uses' => 'ReconsiderationPipelineController@index']);
            Route::get('/under-review-data', ['as' => 'admin.post-completion-pipelines.review-pipeline.under-review-data', 'uses' => 'ReconsiderationPipelineController@underReviewData']);
            Route::get('/waiting-for-approval', ['as' => 'admin.post-completion-pipelines.review-pipeline.waiting-for-approval', 'uses' => 'ReconsiderationPipelineController@waitingForApprovalData']);
        });

        // Final Appraisals to be Mailed
        Route::group(['prefix' => 'mail-pipeline'], function () {
            Route::get('/', ['as' => 'admin.post-completion-pipelines.mail-pipeline', 'uses' => 'MailPipelineController@index']);
            Route::get('/view-row/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.view-row', 'uses' => 'MailPipelineController@viewRow']);
            Route::get('/edit-tracking-number/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.edit-tracking-number', 'uses' => 'MailPipelineController@editTrackingNumber']);
            Route::get('/mark-ready-to-mail/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.mark-ready-to-mail', 'uses' => 'MailPipelineController@markReadyToMail']);
            Route::get('/do-mark-failed/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.do-mark-failed', 'uses' => 'MailPipelineController@doMarkFailed']);
            Route::get('/do-mark-delivered/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.do-mark-delivered', 'uses' => 'MailPipelineController@doMarkDelivered']);
            Route::post('/do-save-tracking-number', ['as' => 'admin.post-completion-pipelines.mail-pipeline.do-save-tracking-number', 'uses' => 'MailPipelineController@doSaveTrackingNumber']);
            Route::post('/create-label', ['as' => 'admin.post-completion-pipelines.mail-pipeline.create-label', 'uses' => 'MailPipelineController@createLabel']);
            Route::post('/do-mark-sent', ['as' => 'admin.post-completion-pipelines.mail-pipeline.do-mark-sent', 'uses' => 'MailPipelineController@doMarkSent']);
            Route::get('/mark-sent-form/{id}', ['as' => 'admin.post-completion-pipelines.mail-pipeline.mark-sent-form', 'uses' => 'MailPipelineController@markSentForm']);
            Route::post('/pending-data', ['as' => 'admin.post-completion-pipelines.mail-pipeline.pending-data', 'uses' => 'MailPipelineController@pendingData']);
            Route::post('/sent-data', ['as' => 'admin.post-completion-pipelines.mail-pipeline.sent-data', 'uses' => 'MailPipelineController@sentData']);
            Route::post('/delivered-data', ['as' => 'admin.post-completion-pipelines.mail-pipeline.delivered-data', 'uses' => 'MailPipelineController@deliveredData']);
            Route::get('/create-pdf-label', ['as' => 'admin.post-completion-pipelines.mail-pipeline.create-pdf-label', 'uses' => 'MailPipelineController@createPdfLabel']);
            Route::get('/download-label', ['as' => 'admin.post-completion-pipelines.mail-pipeline.download-label', 'uses' => 'MailPipelineController@downloadLabel']);
        });
    });


    Route::group(['prefix' => 'appraisal', 'namespace' => 'Appraisal'], function () {
        Route::group(['prefix' => 'ucdp-unit', 'namespace' => 'UCDP'], function () {
            Route::get('/', ['as' => 'admin.appraisal.ucdp-unit', 'uses' => 'UCDPUnitController@index']);
            Route::get(
                'data',
                ['as' => 'admin.appraisal.ucdp-unit.data', 'uses' => 'UCDPUnitController@ucdpData']
            );
            Route::any(
                'create/{ucdpUnit?}',
                ['as' => 'admin.appraisal.ucdp-unit.create', 'uses' => 'UCDPUnitController@createUCDPUnit']
            );
            Route::put(
                'update/{ucdpUnit}',
                ['as' => 'admin.appraisal.ucdp-unit.update', 'uses' => 'UCDPUnitController@updateUCDPUnit']
            );
            Route::get(
                'delete/{ucdpUnit}',
                ['as' => 'admin.appraisal.ucdp-unit.delete', 'uses' => 'UCDPUnitController@deleteUCDPUnit']
            );
            Route::post(
                'deleteFNM',
                ['as' => 'admin.appraisal.ucdp-unit.deleteFNM', 'uses' => 'UCDPUnitController@deleteFNM']
            );
            Route::post(
                'deleteFRE',
                ['as' => 'admin.appraisal.ucdp-unit.deleteFRE', 'uses' => 'UCDPUnitController@deleteFRE']
            );
            Route::post(
                'fnm-edit',
                ['as' => 'admin.appraisal.ucdp-unit.fnm-edit', 'uses' => 'UCDPUnitController@editFnm']
            );
            Route::post(
                'fre-edit',
                ['as' => 'admin.appraisal.ucdp-unit.fre-edit', 'uses' => 'UCDPUnitController@editFre']
            );
        });
        Route::group(['prefix' => 'ead-unit', 'namespace' => 'EAD'], function () {
            Route::get('/', ['as' => 'admin.appraisal.ead-unit', 'uses' => 'EADUnitController@index']);
            Route::get(
                'data',
                ['as' => 'admin.appraisal.ead-unit.data', 'uses' => 'EADUnitController@eadData']
            );
            Route::any(
                'create/{eadUnit?}',
                ['as' => 'admin.appraisal.ead-unit.create', 'uses' => 'EADUnitController@createEADUnit']
            );
            Route::put(
                'update/{eadUnit}',
                ['as' => 'admin.appraisal.ead-unit.update', 'uses' => 'EADUnitController@updateEADUnit']
            );
            Route::get(
                'delete/{eadUnit}',
                ['as' => 'admin.appraisal.ead-unit.delete', 'uses' => 'EADUnitController@deleteEADUnit']
            );
        });

        Route::group(['prefix' => 'under-writing', 'namespace' => 'UW'], function () {
            Route::group(['prefix' => 'checklist'], function () {
                Route::get(
                    '/',
                    ['as' => 'admin.appraisal.under-writing.checklist', 'uses' => 'ChecklistController@index']
                );
                Route::any('/category/create/{category?}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.create',
                    'uses' => 'ChecklistController@createUwCategory'
                ]);
                Route::put('/category/update/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.update',
                    'uses' => 'ChecklistController@updateUwCategory'
                ]);
                Route::get('/category/active-inactive/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.active-inactive',
                    'uses' => 'ChecklistController@categoryMakeActiveInactive'
                ]);
                Route::get('/category/delete/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.delete',
                    'uses' => 'ChecklistController@deleteCategory'
                ]);
                Route::any('/question/create/{question?}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.create',
                    'uses' => 'ChecklistController@createQuestion'
                ]);
                Route::get('/question/active-inactive/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.active-inactive',
                    'uses' => 'ChecklistController@createQuestion'
                ]);
                Route::put('/question/update/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.update',
                    'uses' => 'ChecklistController@updateQuestion'
                ]);
                Route::get('/question/delete/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.delete',
                    'uses' => 'ChecklistController@deleteQuestion'
                ]);
            });
        });

        //Unassigned Pipeline
        Route::group(['prefix' => 'unassigned-pipeline', 'namespace' => 'UnassignedPipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal.unassigned-pipeline', 'uses' => 'UnassignedPipelineController@index']);
            Route::post('/data', ['as' => 'admin.appraisal.unassigned-pipeline.data', 'uses' => 'UnassignedPipelineController@data']);
            Route::post('/mark-as-reviewed', ['as' => 'admin.appraisal.unassigned-pipeline.mark-as-reviewed', 'uses' => 'UnassignedPipelineController@markAsReviewed']);
            Route::post('/mark-priority', ['as' => 'admin.appraisal.unassigned-pipeline.mark-priority', 'uses' => 'UnassignedPipelineController@markPriority']);
        });

        //Purchase & New Construction Pipeline
        Route::group(['prefix' => 'purchase-pipeline', 'namespace' => 'PurchasePipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal.purchase-pipeline', 'uses' => 'PurchasePipelineController@index']);
            Route::post('/data', ['as' => 'admin.appraisal.purchase-pipeline.data', 'uses' => 'PurchasePipelineController@data']);
            Route::post('/mark-as-reviewed', ['as' => 'admin.appraisal.purchase-pipeline.mark-as-reviewed', 'uses' => 'PurchasePipelineController@markAsReviewed']);
            Route::post('/mark-as-worked', ['as' => 'admin.appraisal.purchase-pipeline.mark-as-worked', 'uses' => 'PurchasePipelineController@markAsWorked']);
            Route::post('/mark-as-requested', ['as' => 'admin.appraisal.purchase-pipeline.mark-as-requested', 'uses' => 'PurchasePipelineController@markAsRequested']);
        });

        //Delayed Pipeline
        Route::group(['prefix' => 'delayed-pipeline', 'namespace' => 'DelayedOrdersPipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal.delayed-pipeline', 'uses' => 'DelayedOrdersPipelineController@index']);
            Route::get('data', ['as' => 'admin.appraisal.delayed-pipeline.data', 'uses' => 'DelayedOrdersPipelineController@data']);
            Route::delete('/delete/{id}', ['as' => 'admin.appraisal.delayed-pipeline.delete', 'uses' => 'DelayedOrdersPipelineController@destroy']);
        });

        //Appraisal Pipeline
        Route::group(['prefix' => 'appraisal-pipeline', 'namespace' => 'EscalatedOrdersPipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal.appraisal-pipeline', 'uses' => 'EscalatedOrdersPipelineController@index']);
            Route::post('/filter-data', ['as' => 'admin.appraisal.appraisal-pipeline.filter-data', 'uses' => 'EscalatedOrdersPipelineController@filterData']);
            Route::post('/update-data', ['as' => 'admin.appraisal.appraisal-pipeline.update-data', 'uses' => 'EscalatedOrdersPipelineController@updateData']);
        });

        //Final Appraisals to be Mailed

        Route::group(['prefix' => 'mail-pipeline', 'namespace' => 'MailPipeline'], function () {
            Route::get('/', ['as' => 'admin.appraisal.mail-pipeline', 'uses' => 'MailPipelineController@index']);
            Route::get('/view-row/{id}', ['as' => 'admin.appraisal.mail-pipeline.view-row', 'uses' => 'MailPipelineController@viewRow']);
            Route::get('/edit-tracking-number/{id}', ['as' => 'admin.appraisal.mail-pipeline.edit-tracking-number', 'uses' => 'MailPipelineController@editTrackingNumber']);
            Route::get('/mark-ready-to-mail/{id}', ['as' => 'admin.appraisal.mail-pipeline.mark-ready-to-mail', 'uses' => 'MailPipelineController@markReadyToMail']);
            Route::get('/do-mark-failed/{id}', ['as' => 'admin.appraisal.mail-pipeline.do-mark-failed', 'uses' => 'MailPipelineController@doMarkFailed']);
            Route::get('/do-mark-delivered/{id}', ['as' => 'admin.appraisal.mail-pipeline.do-mark-delivered', 'uses' => 'MailPipelineController@doMarkDelivered']);
            Route::post('/do-save-tracking-number', ['as' => 'admin.appraisal.mail-pipeline.do-save-tracking-number', 'uses' => 'MailPipelineController@doSaveTrackingNumber']);
            Route::post('/create-label', ['as' => 'admin.appraisal.mail-pipeline.create-label', 'uses' => 'MailPipelineController@createLabel']);
            Route::post('/do-mark-sent', ['as' => 'admin.appraisal.mail-pipeline.do-mark-sent', 'uses' => 'MailPipelineController@doMarkSent']);
            Route::get('/mark-sent-form/{id}', ['as' => 'admin.appraisal.mail-pipeline.mark-sent-form', 'uses' => 'MailPipelineController@markSentForm']);
            Route::post('/pending-data', ['as' => 'admin.appraisal.mail-pipeline.pending-data', 'uses' => 'MailPipelineController@pendingData']);
            Route::post('/sent-data', ['as' => 'admin.appraisal.mail-pipeline.sent-data', 'uses' => 'MailPipelineController@sentData']);
            Route::post('/delivered-data', ['as' => 'admin.appraisal.mail-pipeline.delivered-data', 'uses' => 'MailPipelineController@deliveredData']);
            Route::get('/create-pdf-label', ['as' => 'admin.appraisal.mail-pipeline.create-pdf-label', 'uses' => 'MailPipelineController@createPdfLabel']);
            Route::get('/download-label', ['as' => 'admin.appraisal.mail-pipeline.download-label', 'uses' => 'MailPipelineController@downloadLabel']);
        });

        // Aprrasial types
        Route::group(['prefix' => 'appr-types'], function () {
            Route::get('/index', 'TypesController@index')->name('admin.appraisal.appr-types.index');
            Route::get('/data', 'TypesController@data')->name('admin.appraisal.appr-types.data');
            Route::get('/create', 'TypesController@create')->name('admin.appraisal.appr-types.create');
            Route::post('/store', 'TypesController@store')->name('admin.appraisal.appr-types.store');
            Route::get('/edit/{id}', 'TypesController@edit')->name('admin.appraisal.appr-types.edit');
            Route::put('/update/{id}', 'TypesController@update')->name('admin.appraisal.appr-types.update');
        });

        // Aprrasial Order Statuses
        Route::group(['prefix' => 'appr-statuses'], function () {
            Route::get('/index', 'StatusController@index')->name('admin.appraisal.appr-statuses.index');
            Route::get('/data', 'StatusController@data')->name('admin.appraisal.appr-statuses.data');
            Route::get('/create', 'StatusController@create')->name('admin.appraisal.appr-statuses.create');
            Route::post('/store', 'StatusController@store')->name('admin.appraisal.appr-statuses.store');
            Route::get('/edit/{id}', 'StatusController@edit')->name('admin.appraisal.appr-statuses.edit');
            Route::put('/update/{id}', 'StatusController@update')->name('admin.appraisal.appr-statuses.update');
        });
    });


    Route::group(['prefix' => 'document', 'namespace' => 'Documents'], function () {
        Route::group(['prefix' => 'types'], function () {
            Route::get('/', ['as' => 'admin.document.types', 'uses' => 'DocumentTypesController@index']);
            Route::get('data', ['as' => 'admin.document.types.data', 'uses' => 'DocumentTypesController@documentTypesData']);
            Route::any('create/{documentType?}', ['as' => 'admin.document.types.create', 'uses' => 'DocumentTypesController@createDocumentType']);
            Route::put('update/{documentType}', ['as' => 'admin.document.types.update', 'uses' => 'DocumentTypesController@updateDocumentType']);
            Route::get('delete/{documentType}', ['as' => 'admin.document.types.delete', 'uses' => 'DocumentTypesController@deleteDocumentType']);
        });

        Route::group(['prefix' => 'user'], function () {
            Route::group(['prefix' => 'types'], function () {
                Route::get('/', ['as' => 'admin.document.user.types', 'uses' => 'UserDocumentTypesController@index']);
                Route::get('data', ['as' => 'admin.document.user.types.data', 'uses' => 'UserDocumentTypesController@documentUserTypesData']);
                Route::any('create/{userDocumentType?}', ['as' => 'admin.document.user.types.create', 'uses' => 'UserDocumentTypesController@createUserDocumentType']);
                Route::put('update/{userDocumentType}', ['as' => 'admin.document.user.types.update', 'uses' => 'UserDocumentTypesController@updateUserDocumentType']);
                Route::get('delete/{userDocumentType}', ['as' => 'admin.document.user.types.delete', 'uses' => 'UserDocumentTypesController@deleteUserDocumentType']);
            });
        });

        Route::group(['prefix' => 'resource'], function () {
            Route::get('/', ['as' => 'admin.document.resource', 'uses' => 'ResourceDocumentController@index']);
            Route::get('data', ['as' => 'admin.document.resource.data', 'uses' => 'ResourceDocumentController@resourceData']);
            Route::any('create/{resource?}', ['as' => 'admin.document.resource.create', 'uses' => 'ResourceDocumentController@createResource']);
            Route::put('update/{resource}', ['as' => 'admin.document.resource.update', 'uses' => 'ResourceDocumentController@updateResource']);
            Route::get('delete/{resource}', ['as' => 'admin.document.resource.delete', 'uses' => 'ResourceDocumentController@deleteResource']);
        });

        // Upload Manager
        Route::group(['prefix' => 'upload'], function () {
            Route::get('/', ['as' => 'admin.document.upload', 'uses' => 'UploadController@index']);
            Route::get('data', ['as' => 'admin.document.upload.data', 'uses' => 'UploadController@uploadedData']);
            Route::post('upload', ['as' => 'admin.document.upload.upload', 'uses' => 'UploadController@uploadFile']);
            Route::get('delete/{file}', ['as' => 'admin.document.upload.delete', 'uses' => 'UploadController@deleteFile']);
            Route::get('update_status/{file}', ['as' => 'admin.document.upload.update_status', 'uses' => 'UploadController@updateStatus']);
        });

        // Global Documents
        Route::group(['prefix' => 'global'], function () {
            Route::get('index', 'GlobalDocumentsController@index')->name('admin.document.global.index');
            Route::get('create', 'GlobalDocumentsController@create')->name('admin.document.global.create');
            Route::post('store', 'GlobalDocumentsController@store')->name('admin.document.global.store');
            Route::get('data', 'GlobalDocumentsController@data')->name('admin.document.global.data');
            Route::get('delete/{id}', 'GlobalDocumentsController@delete')->name('admin.document.global.delete');
            Route::get('edit/{id}', 'GlobalDocumentsController@edit')->name('admin.document.global.edit');
            Route::put('update/{id}', 'GlobalDocumentsController@update')->name('admin.document.global.update');
        });
    });

    Route::group(['prefix' => 'management', 'namespace' => 'Management'], function () {

        // Admin Teams Manager
        Route::group(['prefix' => 'admin-teams-manager', 'namespace' => 'AdminTeamsManager'], function () {
            Route::get('/', ['as' => 'admin.management.admin-teams-manager', 'uses' => 'AdminTeamsManagerController@index']);
            Route::get('/data', ['as' => 'admin.management.admin-teams-manager.data', 'uses' => 'AdminTeamsManagerController@data']);
            Route::get('/create', ['as' => 'admin.management.admin-teams-manager.create', 'uses' => 'AdminTeamsManagerController@create']);
            Route::post('/create', ['as' => 'admin.management.admin-teams-manager.store', 'uses' => 'AdminTeamsManagerController@store']);
            Route::get('/edit/{id}', ['as' => 'admin.management.admin-teams-manager.edit', 'uses' => 'AdminTeamsManagerController@edit']);
            Route::put('/edit/{id}', ['as' => 'admin.management.admin-teams-manager.update', 'uses' => 'AdminTeamsManagerController@update']);
            Route::delete('/delete/{id}', ['as' => 'admin.management.admin-teams-manager.delete', 'uses' => 'AdminTeamsManagerController@destroy']);
        });


        //Client Settings
        Route::group(['prefix' => 'client', 'namespace' => 'ClientSettings'], function () {
            Route::get('/', ['as' => 'admin.management.client.settings', 'uses' => 'ClientSettingController@index']);
            Route::post('/client/data', ['as' => 'admin.management.client.settings.data', 'uses' => 'ClientSettingController@data']);
            Route::get('/create', ['as' => 'admin.management.client.create', 'uses' => 'ClientSettingController@create']);
            Route::post('/store', ['as' => 'admin.management.client.store', 'uses' => 'ClientSettingController@store']);
            Route::get('/edit/{id}', ['as' => 'admin.management.client.edit', 'uses' => 'ClientSettingController@edit']);
            Route::put('/update/{id}', ['as' => 'admin.management.client.update', 'uses' => 'ClientSettingController@update']);
            Route::get('/download/{id}', ['as' => 'admin.management.client.download', 'uses' => 'ClientSettingController@downloadPdfFile']);
            Route::get('/delete/downloadFile/{id}', ['as' => 'admin.management.client.file.delete', 'uses' => 'ClientSettingController@pdfFileDelete']);
            Route::post('/search-orders', ['as' => 'admin.management.client.search.orders', 'uses' => 'ClientSettingController@searchOrders']);
            Route::post('/client/active', ['as' => 'admin.management.client.change.active', 'uses' => 'ClientSettingController@clientChangeActive']);
            Route::post('/add-note', ['as' => 'admin.management.client.add.note', 'uses' => 'ClientSettingController@addNote']);
            Route::post('/add-user', ['as' => 'admin.management.client.quick.add.user', 'uses' => 'ClientSettingController@addUser']);
            Route::post('/add-ap-logs', ['as' => 'admin.management.client.add.ap.log', 'uses' => 'ClientSettingController@addLog']);
            Route::get('/search-appraisers', ['as' => 'admin.management.client.appraisers', 'uses' => 'ClientSettingController@getAppraisers']);
            Route::get('/search-users', ['as' => 'admin.management.client.users', 'uses' => 'ClientSettingController@getUsers']);
        });


        //Wholesale Lenders
        Route::group(['prefix' => 'lenders', 'namespace' => 'WholesaleLenders'], function () {
            Route::get('/', ['as' => 'admin.management.lenders', 'uses' => 'LendersController@index']);
            Route::get('/data', ['as' => 'admin.management.lenders.data', 'uses' => 'LendersController@data']);
            Route::get('/create', ['as' => 'admin.management.lenders.create', 'uses' => 'LendersController@create']);
            Route::post('/store', ['as' => 'admin.management.lenders.store', 'uses' => 'LendersController@store']);
            Route::get('/edit/{id}', ['as' => 'admin.management.lenders.edit', 'uses' => 'LendersController@edit']);
            Route::get('/delete/{id}', ['as' => 'admin.management.lenders.delete', 'uses' => 'LendersController@delete']);
            Route::put('/update/{id}', ['as' => 'admin.management.lenders.update', 'uses' => 'LendersController@update']);
            Route::post('/import-excluded-users', ['as' => 'admin.management.lenders.import-excluded-users', 'uses' => 'LendersController@importExcludedUsers']);
            Route::post('/add-uw', ['as' => 'admin.management.lenders.add-uw', 'uses' => 'LendersController@addUW']);
            Route::post('/update-uw/{id}', ['as' => 'admin.management.lenders.update-uw', 'uses' => 'LendersController@updateUW']);
            Route::post('/delete-uw/{id}', ['as' => 'admin.management.lenders.delete-uw', 'uses' => 'LendersController@deleteUW']);
            Route::get('/get-client-names', ['as' => 'admin.management.lenders.get-client-names', 'uses' => 'LendersController@getClientNames']);
            Route::post('/add-user-manager', ['as' => 'admin.management.lenders.add-user-manager', 'uses' => 'LendersController@addUserManager']);
            Route::post('/delete-user-manager', ['as' => 'admin.management.lenders.delete-user-manager', 'uses' => 'LendersController@deleteUserManager']);
            Route::get('/get-appraiser-names', ['as' => 'admin.management.lenders.get-appraiser-names', 'uses' => 'LendersController@getAppraiserNames']);
            Route::post('/add-excluded-appraiser', ['as' => 'admin.management.lenders.add-excluded-appraiser', 'uses' => 'LendersController@addExcludedAppraiser']);
            Route::post('/delete-excluded-appraiser', ['as' => 'admin.management.lenders.delete-excluded-appraiser', 'uses' => 'LendersController@deleteExcludedAppraiser']);
            Route::get('/add-proposed/{id}', ['as' => 'admin.management.lenders.add-proposed', 'uses' => 'LendersController@addProposed']);
            Route::post('/add-proposed', ['as' => 'admin.management.lenders.create-proposed', 'uses' => 'LendersController@createProposed']);
            Route::get('/edit-proposed/{id}', ['as' => 'admin.management.lenders.edit-proposed', 'uses' => 'LendersController@editProposed']);
            Route::put('/update-proposed', ['as' => 'admin.management.lenders.update-proposed', 'uses' => 'LendersController@updateProposed']);
            Route::get('/download-template', ['as' => 'admin.management.lenders.download-template', 'uses' => 'LendersController@downloadTemplate']);
            Route::post('/add-user-note', ['as' => 'admin.management.lenders.add-user-note', 'uses' => 'LendersController@addUserNote']);
            Route::delete('/delete-proposed', ['as' => 'admin.management.lenders.delete-proposed', 'uses' => 'LendersController@deleteProposed']);
        });

        Route::group(['prefix' => 'lenders'], function () {
            Route::group(['prefix' => 'exclusionary'], function () {
                Route::get('/', ['as' => 'admin.lenders.exclusionary', 'uses' => 'ExclusionaryController@index']);
                Route::get('data', ['as' => 'admin.lenders.exclusionary.data', 'uses' => 'ExclusionaryController@exclusionaryData']);
                Route::get('licenses/data', ['as' => 'admin.lenders.licenses.data', 'uses' => 'ExclusionaryController@licensesData']);
            });
        });

        Route::group(['prefix' => 'under-writing'], function () {
            Route::group(['prefix' => 'checklist'], function () {
                Route::get(
                    '/',
                    ['as' => 'admin.appraisal.under-writing.checklist', 'uses' => 'UWChecklistController@index']
                );
                Route::any('/category/create/{category?}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.create',
                    'uses' => 'UWChecklistController@createUwCategory'
                ]);
                Route::put('/category/update/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.update',
                    'uses' => 'UWChecklistController@updateUwCategory'
                ]);
                Route::get('/category/active-inactive/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.active-inactive',
                    'uses' => 'UWChecklistController@categoryMakeActiveInactive'
                ]);
                Route::get('/category/delete/{category}', [
                    'as' => 'admin.appraisal.under-writing.checklist.category.delete',
                    'uses' => 'UWChecklistController@deleteCategory'
                ]);
                Route::any('/question/create/{question?}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.create',
                    'uses' => 'UWChecklistController@createQuestion'
                ]);
                Route::get('/question/active-inactive/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.active-inactive',
                    'uses' => 'UWChecklistController@createQuestion'
                ]);
                Route::put('/question/update/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.update',
                    'uses' => 'UWChecklistController@updateQuestion'
                ]);
                Route::get('/question/delete/{question}', [
                    'as' => 'admin.appraisal.under-writing.checklist.question.delete',
                    'uses' => 'UWChecklistController@deleteQuestion'
                ]);
            });
        });

        //QC checklist
        Route::group(['prefix' => 'appr_qc_checklist'], function () {
            Route::get('/', 'QCChecklistController@index')->name('admin.qc.checklist.index');
            Route::get('update_question_status/{question}', 'QCChecklistController@updateQuestionStatus')
                ->name('admin.qc.checklist.update_question_status');
            Route::get('delete/{question}', 'QCChecklistController@destroy')
                ->name('admin.qc.checklist.delete');
            Route::get('update_category_status/{category}', 'QCChecklistController@updateCategoryStatus')
                ->name('admin.qc.checklist.update_category_status');
            Route::get('edit/{question}', 'QCChecklistController@edit')
                ->name('admin.qc.checklist.edit');
            Route::put('update/{question}', 'QCChecklistController@update')
                ->name('admin.qc.checklist.update');
            Route::post('/', 'QCChecklistController@store')
                ->name('admin.qc.checklist.store');
            Route::get('create', 'QCChecklistController@create')
                ->name('admin.qc.checklist.create');
            Route::post('parent_questions_for_category/{id}', 'QCChecklistController@getParentQuestions')
                ->name('admin.qc.checklist.parent_questions');
            Route::get('category/edit/{category}', 'QCChecklistController@editCategory')
                ->name('admin.qc.checklist.edit_category');
            Route::put('category/{category}', 'QCChecklistController@updateCategory')
                ->name('admin.qc.checklist.update_category');
            Route::get('category/create', 'QCChecklistController@createCategory')
                ->name('admin.qc.checklist.create_category');
            Route::post('category', 'QCChecklistController@storeCategory')
                ->name('admin.qc.checklist.store_category');
            Route::post('sort_questions', 'QCChecklistController@sortQuestions')
                ->name('admin.qc.checklist.sort_questions');
            Route::get('client/{client}/change_activity/{activity}', 'QCChecklistController@changeActivityByClient')
                ->name('admin.qc.checklist.client.change_activity');
            Route::get('lender/{lender}/change_activity/{activity}', 'QCChecklistController@changeActivityByLender')
                ->name('admin.qc.checklist.lender.change_activity');
            Route::post('clients_data', 'QCChecklistController@clientsData')
                ->name('admin.qc.checklist.clients_data');
            Route::post('lenders_data', 'QCChecklistController@lendersData')
                ->name('admin.qc.checklist.lenders_data');
        });

        // QC data collection
        Route::group(['prefix' => 'appr_qc_data_collection'], function () {
            Route::get('/', 'DataCollectionController@index')->name('admin.qc.collection.index');
            Route::get('create', 'DataCollectionController@create')->name('admin.qc.collection.create');
            Route::get('data', 'DataCollectionController@data')->name('admin.qc.collection.data');
            Route::get('edit/{row}', 'DataCollectionController@edit')->name('admin.qc.collection.edit');
            Route::get('delete/{question}', 'DataCollectionController@destroy')->name('admin.qc.collection.delete');
            Route::put('update/{question}', 'DataCollectionController@update')->name('admin.qc.collection.update');
            Route::post('/', 'DataCollectionController@store')->name('admin.qc.collection.store');
        });

        // ASC LIcenses
        Route::group(['prefix' => 'asc-licenses'], function () {
            Route::get('/', ['as' => 'admin.management.asc-licenses', 'uses' => 'ASCLicensesController@index']);
            Route::post('data', ['as' => 'admin.management.asc-licenses.data', 'uses' => 'ASCLicensesController@ascLicensesData']);
        });

        Route::group(['prefix' => 'email-templates'], function () {
            Route::get('/', ['as' => 'admin.management.email-templates', 'uses' => 'EmailTemplatesController@index']);
            Route::get('data', ['as' => 'admin.management.email-templates.data', 'uses' => 'EmailTemplatesController@emailTemplatesData']);
            Route::any(
                'create/{emailTemplate?}',
                [
                    'as' => 'admin.management.email-templates.create',
                    'uses' => 'EmailTemplatesController@createEmailTemplate'
                ]
            );
            Route::put(
                'update/{emailTemplate}',
                [
                    'as' => 'admin.management.email-templates.update',
                    'uses' => 'EmailTemplatesController@updateEmailTemplate'
                ]
            );
            Route::get(
                'delete/{emailTemplate}',
                [
                    'as' => 'admin.management.email-templates.delete',
                    'uses' => 'EmailTemplatesController@deleteEmailTemplate'
                ]
            );
        });

        Route::group(['prefix' => 'user-templates'], function () {
            Route::get('/', ['as' => 'admin.management.user-templates', 'uses' => 'UserTemplatesController@index']);
            Route::get(
                'data',
                [
                    'as' => 'admin.management.user-templates.data',
                    'uses' => 'UserTemplatesController@userTemplatesData'
                ]
            );
            Route::any(
                'create/{userTemplate?}',
                [
                    'as' => 'admin.management.user-templates.create',
                    'uses' => 'UserTemplatesController@createUserTemplate'
                ]
            );
            Route::put(
                'update/{userTemplate}',
                [
                    'as' => 'admin.management.user-templates.update',
                    'uses' => 'UserTemplatesController@updateUserTemplate'
                ]
            );
            Route::get(
                'delete/{userTemplate}',
                [
                    'as' => 'admin.management.user-templates.delete',
                    'uses' => 'UserTemplatesController@deleteUserTemplate'
                ]
            );
        });

        Route::group(['prefix' => 'zipcodes'], function () {
            Route::get(
                '/',
                ['as' => 'admin.management.zipcodes', 'uses' => 'ZipCodesController@index']
            );
            Route::get(
                'data',
                ['as' => 'admin.management.zipcodes.data', 'uses' => 'ZipCodesController@zipCodeData']
            );
            Route::any(
                'create/{ZipCode?}',
                ['as' => 'admin.management.zipcodes.create', 'uses' => 'ZipCodesController@createZipCode']
            );
            Route::put(
                'update/{ZipCode}',
                ['as' => 'admin.management.zipcodes.update', 'uses' => 'ZipCodesController@updateZipCode']
            );
            Route::get(
                'delete/{ZipCode}',
                ['as' => 'admin.management.zipcodes.delete', 'uses' => 'ZipCodesController@deleteZipCode']
            );
        });

        Route::group(['prefix' => 'custom-email-templates'], function () {
            Route::get(
                '/',
                ['as' => 'admin.management.custom-email-templates', 'uses' => 'CustomEmailTemplatesController@index']
            );
            Route::get(
                'data',
                [
                    'as' => 'admin.management.custom-email-templates.data',
                    'uses' => 'CustomEmailTemplatesController@customEmailTemplatesData'
                ]
            );
            Route::any(
                'create/{customEmailTemplate?}',
                [
                    'as' => 'admin.management.custom-email-templates.create',
                    'uses' => 'CustomEmailTemplatesController@createCustomEmailTemplate'
                ]
            );
            Route::put(
                'update/{customEmailTemplate}',
                [
                    'as' => 'admin.management.custom-email-templates.update',
                    'uses' => 'CustomEmailTemplatesController@updateCustomEmailTemplate'
                ]
            );
            Route::get(
                'delete/{customEmailTemplate}',
                [
                    'as' => 'admin.management.custom-email-templates.delete',
                    'uses' => 'CustomEmailTemplatesController@deleteCustomEmailTemplate'
                ]
            );
        });
        Route::group(['prefix' => 'groups'], function () {
            Route::get(
                '/',
                ['as' => 'admin.management.groups.index', 'uses' => 'GroupsController@index']
            );
            Route::get(
                'data',
                ['as' => 'admin.management.groups.data', 'uses' => 'GroupsController@data']
            );
            Route::get(
                'create',
                ['as' => 'admin.management.groups.create', 'uses' => 'GroupsController@create']
            );
            Route::post(
                'store',
                ['as' => 'admin.management.groups.store', 'uses' => 'GroupsController@store']
            );
            Route::get(
                '{id}/edit',
                ['as' => 'admin.management.groups.edit', 'uses' => 'GroupsController@edit']
            );
            Route::patch(
                '{id}',
                ['as' => 'admin.management.groups.update', 'uses' => 'GroupsController@update']
            );
            Route::get(
                '{group}',
                ['as' => 'admin.management.groups.delete', 'uses' => 'GroupsController@delete']
            );
        });

        //Admin Groups
        Route::group(['prefix' => 'admin-groups'], function () {
            Route::get('/', ['as' => 'admin.management.admin-groups', 'uses' => 'AdminGroupController@index']);
            Route::get('data', ['as' => 'admin.management.admin-groups.data', 'uses' => 'AdminGroupController@data']);
            Route::get('/create', ['as' => 'admin.management.admin-groups.create', 'uses' => 'AdminGroupController@create']);
            Route::post('/create', ['as' => 'admin.management.admin-groups.store', 'uses' => 'AdminGroupController@store']);
            Route::get('{id}/edit', ['as' => 'admin.management.admin-groups.edit', 'uses' => 'AdminGroupController@edit']);
            Route::put('/update/{id}', ['as' => 'admin.management.admin-groups.update', 'uses' => 'AdminGroupController@update']);
            Route::post('/delete', ['as' => 'admin.management.admin-groups.delete', 'uses' => 'AdminGroupController@destroy']);
            Route::get('/clear-cache', ['as' => 'admin.management.admin-groups.clear-cache', 'uses' => 'AdminGroupController@clearCache']);
        });

        //Active Users
        Route::group(['prefix' => 'appraiser-groups', 'namespace' => 'ActiveUsers'], function () {
            Route::get('/', ['as' => 'admin.management.active-users', 'uses' => 'ActiveUsersController@index']);
        });

        // AppraiserGroupsController
        Route::group(['prefix' => 'appraiser-groups'], function () {
            Route::get('index', 'AppraiserGroupsController@index')->name('admin.management.appraiser.index');
            Route::get('data', 'AppraiserGroupsController@data')->name('admin.management.appraiser.data');
            Route::get('create', 'AppraiserGroupsController@create')->name('admin.management.appraiser.create');
            Route::post('store', 'AppraiserGroupsController@store')->name('admin.management.appraiser.store');
            Route::get('managers', 'AppraiserGroupsController@getManagers')->name('admin.management.appraiser.managers');
            Route::get('appraisers', 'AppraiserGroupsController@getAppraisers')->name('admin.management.appraiser.appraisers');
            Route::post('appraisers', 'AppraiserGroupsController@storeAppraiser')->name('admin.management.appraiser.appraisers.store');
            Route::delete('appraisers', 'AppraiserGroupsController@destroyAppraiser')->name('admin.management.appraiser.appraisers.destroy');
            Route::get('edit/{id}', 'AppraiserGroupsController@edit')->name('admin.management.appraiser.edit');
            Route::put('{id}', 'AppraiserGroupsController@update')->name('admin.management.appraiser.update');
        });

        Route::group(['prefix' => 'announcements'], function () {
            Route::get('/', ['as' => 'admin.management.announcements', 'uses' => 'AnnouncementsController@index']);
            Route::get(
                'data',
                ['as' => 'admin.management.announcements.data', 'uses' => 'AnnouncementsController@announcementsData']
            );
            Route::post(
                'user-types',
                [
                    'as' => 'admin.management.announcements.user-types',
                    'uses' => 'AnnouncementsController@userTypesData'
                ]
            );
            Route::post(
                'viewed',
                ['as' => 'admin.management.announcements.viewed', 'uses' => 'AnnouncementsController@viewedData']
            );
            Route::any(
                'create/{announcement?}',
                [
                    'as' => 'admin.management.announcements.create',
                    'uses' => 'AnnouncementsController@createAnnouncement'
                ]
            );
            Route::put(
                'update/{announcement}',
                [
                    'as' => 'admin.management.announcements.update',
                    'uses' => 'AnnouncementsController@updateAnnouncement'
                ]
            );
            Route::get(
                'delete/{announcement}',
                [
                    'as' => 'admin.management.announcements.delete',
                    'uses' => 'AnnouncementsController@deleteAnnouncement'
                ]
            );
        });

        Route::group(['prefix' => 'surveys'], function () {
            Route::get(
                '/',
                ['as' => 'admin.management.surveys.index', 'uses' => 'SurveysController@index']
            );
            Route::get(
                'data',
                ['as' => 'admin.management.surveys.data', 'uses' => 'SurveysController@data']
            );
            Route::get(
                'create',
                ['as' => 'admin.management.surveys.create', 'uses' => 'SurveysController@create']
            );
            Route::post(
                'store',
                ['as' => 'admin.management.surveys.store', 'uses' => 'SurveysController@store']
            );
            Route::get(
                '{id}/edit',
                ['as' => 'admin.management.surveys.edit', 'uses' => 'SurveysController@edit']
            );
            Route::get(
                '{survey}/questions',
                ['as' => 'admin.management.surveys.show.questions', 'uses' => 'SurveysController@questions']
            );
            Route::patch(
                '{id}',
                ['as' => 'admin.management.surveys.update', 'uses' => 'SurveysController@update']
            );
            Route::get(
                '{survey}',
                ['as' => 'admin.management.surveys.delete', 'uses' => 'SurveysController@delete']
            );

            /**QUESTIONS From Survey page**/
            Route::group(['prefix' => 'questions'], function () {
                Route::get(
                    'data/{id?}',
                    ['as' => 'admin.management.surveys.questions.data', 'uses' => 'SurveyQuestionsController@data']
                );
                Route::get(
                    'create/{id?}',
                    [
                        'as' => 'admin.management.surveys.questions.create',
                        'uses' => 'SurveyQuestionsController@create'
                    ]
                );
                Route::post(
                    'store',
                    ['as' => 'admin.management.surveys.questions.store', 'uses' => 'SurveyQuestionsController@store']
                );
                Route::get(
                    '{id}/edit',
                    ['as' => 'admin.management.surveys.questions.edit', 'uses' => 'SurveyQuestionsController@edit']
                );
                Route::patch(
                    '{id}',
                    [
                        'as' => 'admin.management.surveys.questions.update',
                        'uses' => 'SurveyQuestionsController@update'
                    ]
                );
                Route::get(
                    '{question}',
                    [
                        'as' => 'admin.management.surveys.questions.delete',
                        'uses' => 'SurveyQuestionsController@delete'
                    ]
                );
            });
        });

        Route::group(['prefix' => 'survey-report'], function () {
            Route::get(
                '/',
                ['as' => 'admin.management.surveys.answers.index', 'uses' => 'SurveyAnswersController@index']
            );
            Route::get(
                'data',
                ['as' => 'admin.management.surveys.answers.data', 'uses' => 'SurveyAnswersController@data']
            );
            Route::get(
                '{id}/answers',
                ['as' => 'admin.management.surveys.answers.show', 'uses' => 'SurveyAnswersController@show']
            );
            Route::get(
                '{survey_id}/answers/data',
                [
                    'as' => 'admin.management.surveys.answers.show.data',
                    'uses' => 'SurveyAnswersController@answersData'
                ]
            );
            Route::post(
                'answers/report',
                ['as' => 'admin.management.surveys.answers.report', 'uses' => 'SurveyAnswersController@report']
            );
        });
    });


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
    });

    Route::group(['prefix' => 'geo', 'namespace' => 'Geo'], function () {

        // AddressGeoCodeController
        Route::group(['prefix' => 'address'], function () {
            Route::get('/', ['as' => 'admin.geo.address', 'uses' => 'AddressGeoCodeController@index']);
            Route::get('data', ['as' => 'admin.geo.address.data', 'uses' => 'AddressGeoCodeController@geoCodeData']);
            Route::any('create/{addressGeoCode?}', ['as' => 'admin.geo.address.create', 'uses' => 'AddressGeoCodeController@geoCodeData']);
            Route::any('create_geo_code', ['as' => 'admin.geo.address.create_geo_code', 'uses' => 'AddressGeoCodeController@createGeoCode']);
            Route::get('refresh/{addressGeoCode}', ['as' => 'admin.geo.address.refresh', 'uses' => 'AddressGeoCodeController@refreshGeoCode']);
            Route::get('delete/{addressGeoCode}', ['as' => 'admin.geo.address.delete', 'uses' => 'AddressGeoCodeController@deleteGeoCode']);
        });

        Route::group(['prefix' => 'google-coding'], function () {
            Route::get('index', 'GoogleCodingsController@index')->name('admin.geo.google-coding.index');
            Route::get('data', 'GoogleCodingsController@data')->name('admin.geo.google-coding.data');
            Route::get('geo-code/{id}', 'GoogleCodingsController@geoCode')->name('admin.geo.google-coding.geo-code');
        });
    });

    Route::group(['prefix' => 'tiger', 'namespace' => 'Tiger'], function () {
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

    // Accounting
    Route::group(['prefix' => 'accounting', 'namespace' => 'Accounting'], function () {

        // Accounting General Reports
        Route::group(['prefix' => 'general-reports'], function () {
            Route::get('/', 'GeneralReportController@index')->name('admin.accounting.general-reports.index');
            Route::post('/export', 'GeneralReportController@export')->name('admin.accounting.general-reports.export');
        });

        // Accounting Reports
        Route::group(['prefix' => 'reports'], function () {
            Route::get('/', 'ReportController@index')->name('admin.accounting.reports.index');
            Route::post('/export', 'ReportController@export')->name('admin.accounting.reports.export');
            Route::post('/data', 'ReportController@data')->name('admin.accounting.reports.data');
        });
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', 'UsersController@index')->name('admin.users.index');
            Route::get('/{user}', 'UsersController@show')->name('admin.users.show');
            Route::get('/{user}/download/{document}', 'UsersController@download')
                ->name('admin.users.download');
            Route::get('/reset-password-link/{user}', 'UsersController@resetPasswordLink')
                ->name('admin.users.reset_password_link');
            Route::post('/{user}/update', 'UsersController@update')->name('admin.users.update');
            Route::post('/{user}/add-note', 'UsersController@addNote')
                ->name('admin.users.add-note');
            Route::post('/{user}/load-user-logs', 'UsersController@loadUserLogs')
                ->name('admin.users.load-user-logs');
            Route::post('/{user}/delete-user-logs', 'UsersController@deleteUserLogs')
                ->name('admin.users.delete-user-logs');
            Route::post('/{user}/get-email-template', 'UsersController@getEmailTemplate')
                ->name('admin.users.get-email-template');
            Route::post('/{user}/send-email', 'UsersController@sendEmail')
                ->name('admin.users.send-email');
            Route::post('/{user}/add-cc-card', 'UsersController@addCard')
                ->name('admin.users.add-card');
            Route::post('/{user}/backgroundcheck-upload', 'UsersController@backgroundCheckUpload')
                ->name('admin.users.background_check_upload');
            Route::get('/document/{id}/download/{kind}', 'UsersController@documentDownload')
                ->name('admin.users.document-download');
            Route::get('/document/{row}/view', 'UsersController@documentView')
                ->name('admin.users.document-view');
            Route::post('/{user}/additional-document', 'UsersController@additionalDocument')
                ->name('admin.users.additional_document');
            Route::post('/{user}/eando-upload', 'UsersController@eandoUpload')
                ->name('admin.users.eando-upload');
        });
        Route::get('/reset-password', 'UsersController@resetPassword')
            ->name('admin.reset_password');

        //Export Check
        Route::group(['prefix' => 'export_check'], function () {
            Route::get('/', 'ExportCheckController@index')
                ->name('admin.export_check.index');
            Route::post('export', 'ExportCheckController@export')
                ->name('admin.export_check.export');
        });

        // Accounting Payable Reports
        Route::group(['prefix' => 'payable-reports'], function () {
            Route::get('/', 'PayableReportController@index')->name('admin.accounting.payable-reports.index');
            Route::post('/export', 'PayableReportController@export')->name('admin.accounting.payable-reports.export');
            Route::post('/data', 'PayableReportController@data')->name('admin.accounting.payable-reports.data');
        });

        // Accounting Payable Manager
        Route::group(['prefix' => 'payable-manager'], function () {
            Route::get('/', 'PayableManagerController@index')->name('admin.accounting.payable-manager.index');
            Route::post('/data', 'PayableManagerController@data')->name('admin.accounting.payable-manager.data');
            Route::post('/apply-payment', 'PayableManagerController@applyPaymnet')->name('admin.accounting.payable-manager.apply-payment');
            Route::post('/download', 'PayableManagerController@download')->name('admin.accounting.payable-manager.download');
            Route::get('/read', 'PayableManagerController@read')->name('admin.accounting.payable-manager.read');
            Route::get('/download-document/{document}', 'PayableManagerController@downloadDocument')->name('admin.accounting.payable-manager.download.document');
        });

        // DocuVault Receivables Controller
        Route::group(['prefix' => 'docuvault-receivables'], function () {
            Route::get('/', 'DocuVaultReceivablesController@index')->name('admin.accounting.docuvault-receivables.index');
            Route::get('/data', 'DocuVaultReceivablesController@data')->name('admin.accounting.docuvault-receivables.data');
            Route::get('/show', 'DocuVaultReceivablesController@show')->name('admin.accounting.docuvault-receivables.show');
            Route::post('/download', 'DocuVaultReceivablesController@download')->name('admin.accounting.docuvault-receivables.download');
            Route::post('/statments', 'DocuVaultReceivablesController@statments')->name('admin.accounting.docuvault-receivables.statments');
        });

        // AL Accounts Payable Reports
        Route::group(['prefix' => 'al-payable-reports'], function () {
            Route::get('/', 'AlPayableReportController@index')->name('admin.accounting.al-payable-reports.index');
        });

        // Accounts Receivable Reports
        Route::group(['prefix' => 'receivable-reports'], function () {
            Route::get('/', 'ReceivableReportController@index')->name('admin.accounting.receivable-reports.index');
            Route::post('invoiced', 'ReceivableReportController@getInvoiced')->name('admin.accounting.receivable-reports.invoiced');
            Route::post('noninvoiced', 'ReceivableReportController@getNonInvoiced')->name('admin.accounting.receivable-reports.noninvoiced');
            Route::get('view-clients', 'ReceivableReportController@viewClients')->name('admin.accounting.receivable-reports.view-clients');
            Route::get('view-clients-report', 'ReceivableReportController@viewClientsReport')->name('admin.accounting.receivable-reports.clients-report');
        });

        //Export Check
        Route::group(['prefix' => 'export-check'], function () {
            Route::get('/', 'ExportCheckController@index')->name('admin.accounting.export-check.index');
            Route::post('/export', 'ExportCheckController@export')->name('admin.accounting.export-check.export');
        });

        //Locate payments
        Route::group(['prefix' => 'locate-payments'], function () {
            Route::get('/', 'LocatePaymentsController@index')->name('admin.accounting.locate-payments.index');
        });

        // Daily Batch
        Route::group(['prefix' => 'daily-batch'], function () {
            Route::get('/', 'DailyBatchController@index')->name('admin.accounting.daily-batch.index');
            Route::post('/appr-credit-cards', 'DailyBatchController@apprCreditCards')->name('admin.accounting.daily-batch.appr-credit-cards');
            Route::post('appr-checks', 'DailyBatchController@apprChecks')->name('admin.accounting.daily-batch.appr-checks');
            Route::post('mercury', 'DailyBatchController@mercury')->name('admin.accounting.daily-batch.mercury');
            Route::post('alt-credit-cards', 'DailyBatchController@altCreditCards')->name('admin.accounting.daily-batch.alt-credit-cards');
            Route::post('alt-checks', 'DailyBatchController@altChecks')->name('admin.accounting.daily-batch.alt-checks');
            Route::post('docuvault-checks', 'DailyBatchController@docuvaultChecks')->name('admin.accounting.daily-batch.docuvault-checks');
            Route::post('adjustments', 'DailyBatchController@getAdjustments')->name('admin.accounting.daily-batch.adjustments');
            Route::post('export', 'DailyBatchController@export')->name('admin.accounting.daily-batch.export');
        });

        // Batch Check Payment
        Route::group(['prefix' => 'batch-check'], function () {
            Route::get('/', 'BatchCheckController@index')->name('admin.accounting.batch-check.index');
            Route::post('/data', 'BatchCheckController@data')->name('admin.accounting.batch-check.data');
            Route::post('/apply-batch-check', 'BatchCheckController@applyBatchCheck')->name('admin.accounting.batch-check.apply-batch-check');
            Route::post('/apply-batch-cc', 'BatchCheckController@applyBatchCC')->name('admin.accounting.batch-check.apply-batch-cc');
        });

        // Batch Check Payment
        Route::group(['prefix' => 'batch-docuvault-check'], function () {
            Route::get('/', 'BatchDocuvaultCheckController@index')->name('admin.accounting.batch-docuvault-check.index');
            Route::post('show-orders', 'BatchDocuvaultCheckController@showOrders')->name('admin.accounting.batch-docuvault-check.show-orders');
            Route::post('apply-batch-check', 'BatchDocuvaultCheckController@applyBatchCheck')->name('admin.accounting.batch-docuvault-check.apply-batch-check');
            Route::post('apply-batch-cc-check', 'BatchDocuvaultCheckController@applyBatchCheckCredit')->name('admin.accounting.batch-docuvault-check.apply-batch-cc-check');
        });

        // Payable
        Route::group(['prefix' => 'payables'], function () {
            Route::get('/', 'PayableController@index')->name('admin.accounting.payable.index');
            Route::get('{payment}/show', 'PayableController@show')->name('admin.accounting.payable.show');
        });

        // Account Payable Revert
        Route::group(['prefix' => 'payable-revert'], function () {
            Route::get('/', 'AccountPayableRevertController@index')->name('admin.accounting.payable-revert.index');
            Route::post('/data', 'AccountPayableRevertController@data')->name('admin.accounting.payable-revert.data');
            Route::post('/revert', 'AccountPayableRevertController@revert')->name('admin.accounting.payable-revert.revert');
        });

        // Vendor Tax Info
        Route::group(['prefix' => 'vendor_tax_info'], function () {
            Route::get('/', 'VendorTaxInfoController@index')->name('admin.vendor_tax_info.index');
            Route::post('export', 'VendorTaxInfoController@export')->name('admin.vendor_tax_info.export');
        });
    });

    // Tickets
    Route::group(['prefix' => 'ticket', 'namespace' => 'Ticket'], function () {

        // Ticket Status
        Route::group(['prefix' => 'statuses'], function () {
            Route::get(
                '/',
                ['as' => 'admin.ticket.statuses.index', 'uses' => 'TicketStatusesController@index']
            );
            Route::get(
                'data',
                ['as' => 'admin.ticket.statuses.data', 'uses' => 'TicketStatusesController@data']
            );
            Route::get(
                'create',
                ['as' => 'admin.ticket.statuses.create', 'uses' => 'TicketStatusesController@create']
            );
            Route::post(
                'store',
                ['as' => 'admin.ticket.statuses.store', 'uses' => 'TicketStatusesController@store']
            );
            Route::get(
                '{id}/edit',
                ['as' => 'admin.ticket.statuses.edit', 'uses' => 'TicketStatusesController@edit']
            );
            Route::patch(
                '{id}',
                ['as' => 'admin.ticket.statuses.update', 'uses' => 'TicketStatusesController@update']
            );
        });

        // Ticket Stats
        Route::group(['prefix' => 'stats'], function () {
            Route::get('ticket_stats', 'TicketStatsController@index')->name('admin.ticket.stats.index');
            Route::post('ticket_stats', 'TicketStatsController@postGetData')->name('admin.ticket.stats.data');
            Route::post('ticket_stats_export', 'TicketStatsController@excelExport')->name('admin.ticket.stats.export');
        });

        // Ticket Categories
        Route::group(['prefix' => 'categories'], function () {
            Route::get(
                '/',
                ['as' => 'admin.ticket.categories.index', 'uses' => 'CategoriesController@index']
            );
            Route::get(
                '/{id}/edit',
                ['as' => 'admin.ticket.categories.edit', 'uses' => 'CategoriesController@edit']
            );
            Route::put(
                '/{id}',
                ['as' => 'admin.ticket.categories.update', 'uses' => 'CategoriesController@update']
            );
            Route::delete(
                '/{id}',
                ['as' => 'admin.ticket.categories.delete', 'uses' => 'CategoriesController@destroy']
            );
            Route::get(
                'data',
                ['as' => 'admin.ticket.categories.data', 'uses' => 'CategoriesController@data']
            );
            Route::get(
                'create',
                ['as' => 'admin.ticket.categories.create', 'uses' => 'CategoriesController@create']
            );
            Route::post(
                'create',
                ['as' => 'admin.ticket.categories.store', 'uses' => 'CategoriesController@store']
            );
        });

        // Ticket Manager
        Route::group(['prefix' => 'manager'], function () {
            Route::get('/', ['as' => 'admin.ticket.manager', 'uses' => 'ManagerController@index']);
            // View ticket
            Route::any('view/{ticket}', [
                'as' => 'admin.ticket.manager.view',
                'uses' => 'ManagerController@view'
            ]);
            // View ticket content
            Route::get('view_ticket_content/{ticket}', [
                'as' => 'admin.ticket.manager.view_ticket_content',
                'uses' => 'ManagerController@viewTicketContent'
            ]);
            // Download Document
            Route::get('download_document/{file}', [
                'as' => 'admin.ticket.manager.download_document',
                'uses' => 'ManagerController@downloadDocument'
            ]);
            // View Image
            Route::get('view_image', [
                'as' => 'admin.ticket.manager.view_image',
                'uses' => 'ManagerController@viewImage'
            ]);
            // Get Tickets
            Route::get('get_tickets', [
                'as' => 'admin.ticket.manager.get_tickets',
                'uses' => 'ManagerController@getTickets'
            ]);
            // Get Activity Tab
            Route::get('get_activity_tab', [
                'as' => 'admin.ticket.manager.get_activity_tab',
                'uses' => 'ManagerController@getActivityTab'
            ]);
            // Get Stats Tab
            Route::get('get_stats_tab', [
                'as' => 'admin.ticket.manager.get_stats_tab',
                'uses' => 'ManagerController@getStatsTab'
            ]);
            // Get Currently Viewing Update
            Route::get('get_currently_viewing_update/{ticket}', [
                'as' => 'admin.ticket.manager.get_currently_viewing_update',
                'uses' => 'ManagerController@getCurrentlyViewingUpdate'
            ]);
            // Unlock Ticket
            Route::post('unlock_ticket', [
                'as' => 'admin.ticket.manager.unlock_ticket',
                'uses' => 'ManagerController@unlockTicket'
            ]);
            // Close Ticket
            Route::post('close_ticket/{ticket}', [
                'as' => 'admin.ticket.manager.close_ticket',
                'uses' => 'ManagerController@closeTicket'
            ]);
            // Open Ticket
            Route::post('open_ticket/{ticket}', [
                'as' => 'admin.ticket.manager.open_ticket',
                'uses' => 'ManagerController@openTicket'
            ]);
            // Get Email Template
            Route::get('get_email_template', [
                'as' => 'admin.ticket.manager.get_email_template',
                'uses' => 'ManagerController@getEmailTemplate'
            ]);
            // Get Comment Content
            Route::get('get_comment_content/{comment}', [
                'as' => 'admin.ticket.manager.get_comment_content',
                'uses' => 'ManagerController@getCommentContent'
            ]);
            // Get Ticket Comments
            Route::get('get_ticket_comments', [
                'as' => 'admin.ticket.manager.get_ticket_comments',
                'uses' => 'ManagerController@getTicketComments'
            ]);
            // Set Comment Visibility
            Route::post('set_comment_visibility/{comment}', [
                'as' => 'admin.ticket.manager.set_comment_visibility',
                'uses' => 'ManagerController@setCommentVisibility'
            ]);
            // Search Order
            Route::get('search_order', [
                'as' => 'admin.ticket.manager.search_order',
                'uses' => 'ManagerController@searchOrder'
            ]);
            // Remove Participant User
            Route::post('remove_participant_user', [
                'as' => 'admin.ticket.manager.remove_participant_user',
                'uses' => 'ManagerController@removeParticipantUser'
            ]);
            // Find Mentions
            Route::get('find_mentions', [
                'as' => 'admin.ticket.manager.find_mentions',
                'uses' => 'ManagerController@findMentions'
            ]);
            // Inline Category Edit
            Route::get('inline_category_edit', [
                'as' => 'admin.ticket.manager.inline_category_edit',
                'uses' => 'ManagerController@inlineCategoryEdit'
            ]);
            // Inline Status Edit
            Route::get('inline_status_edit', [
                'as' => 'admin.ticket.manager.inline_status_edit',
                'uses' => 'ManagerController@inlineStatusEdit'
            ]);
            // Inline Assign Edit
            Route::post('inline_assign_edit', [
                'as' => 'admin.ticket.manager.inline_assign_edit',
                'uses' => 'ManagerController@inlineAssignEdit'
            ]);
            // Get Multi Moderation Form
            Route::get('get_multi_moderation_form', [
                'as' => 'admin.ticket.manager.get_multi_moderation_form',
                'uses' => 'ManagerController@getMultiModerationForm'
            ]);
            // Process Multi Moderation Form
            Route::post('process_multi_moderation_form', [
                'as' => 'admin.ticket.manager.process_multi_moderation_form',
                'uses' => 'ManagerController@processMultiModerationForm'
            ]);
            // Get Multi Mod Record
            Route::get('get_multi_mod_record/{mod}', [
                'as' => 'admin.ticket.manager.get_multi_mod_record',
                'uses' => 'ManagerController@getMultiModRecord'
            ]);
            // Get Multi Mod Index Form
            Route::get('get_multi_mod_index_form/{mod}', [
                'as' => 'admin.ticket.manager.get_multi_mod_index_form',
                'uses' => 'ManagerController@getMultiModIndexForm'
            ]);
            // Apply Multi Mod Index
            Route::post('apply_multi_mod_index/{mod}', [
                'as' => 'admin.ticket.manager.apply_multi_mod_index',
                'uses' => 'ManagerController@applyMultiModIndex'
            ]);
        });

        // Ticket Rules
        Route::group(['prefix' => 'rule'], function () {
            Route::get('/', ['as' => 'admin.ticket.rule', 'uses' => 'RuleController@index']);
            // Data
            Route::get('data', [
                'as' => 'admin.ticket.rule.data',
                'uses' => 'RuleController@data'
            ]);
            // Create
            Route::any('create', [
                'as' => 'admin.ticket.rule.create',
                'uses' => 'RuleController@create'
            ]);
            // Update
            Route::any('update/{rule}', [
                'as' => 'admin.ticket.rule.update',
                'uses' => 'RuleController@update'
            ]);
            // Delete
            Route::get('delete/{rule}', [
                'as' => 'admin.ticket.rule.delete',
                'uses' => 'RuleController@delete'
            ]);
        });

        // Ticket Multi-Moderation
        Route::group(['prefix' => 'moderation'], function () {
            Route::get('/', ['as' => 'admin.ticket.moderation', 'uses' => 'ModerationController@index']);
            // Data
            Route::get('data', [
                'as' => 'admin.ticket.moderation.data',
                'uses' => 'ModerationController@data'
            ]);
            // Create
            Route::any('create', [
                'as' => 'admin.ticket.moderation.create',
                'uses' => 'ModerationController@create'
            ]);
            // Update
            Route::any('update/{mod}', [
                'as' => 'admin.ticket.moderation.update',
                'uses' => 'ModerationController@update'
            ]);
            // Delete
            Route::get('delete/{mod}', [
                'as' => 'admin.ticket.moderation.delete',
                'uses' => 'ModerationController@delete'
            ]);
        });
    });

    $path = Module::getModulePath('Admin') . 'Routes/';
    foreach (glob($path . '*.php') as $route) {
        require $route;
    }

});
