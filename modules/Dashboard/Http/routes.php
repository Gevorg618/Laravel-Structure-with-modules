<?php

Route::group([
    'middleware' => 'web',
    'prefix' => 'dashboard',
    'as' => 'dashboard.',
    'namespace' => 'Dashboard\Http\Controllers'
], function () {

    Route::get('/', 'DashboardController@index');
    Route::get('/login/reset/{token}', ['as' => 'reset', 'uses' => 'DashboardController@index']);

    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'Api\IndexController@index']);

        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
          Route::post('/login', ['as' => 'login', 'uses' => 'Api\Auth\AuthController@login']);
          Route::post('/logout', ['as' => 'logout', 'uses' => 'Api\Auth\AuthController@logout']);
          Route::any('/reset', ['as' => 'reset', 'uses' => 'Api\Auth\ResetPasswordController@reset']);
          Route::any('/reset/confirm', ['as' => 'reset.confirm', 'uses' => 'Api\Auth\ResetPasswordController@confirm']);
          Route::post('/reset/complete', ['as' => 'reset.complete', 'uses' => 'Api\Auth\ResetPasswordController@resetComplete']);
        });

        Route::group(['middleware' => ['dashboard.auth']], function () {
          Route::group(['prefix' => 'appraisals', 'as' => 'appraisals.'], function () {
            Route::get('/list', ['as' => 'list', 'uses' => 'Api\Appraisals\OrdersController@list']);
            Route::get('/company', ['as' => 'company', 'uses' => 'Api\Appraisals\OrdersController@company']);
          });

          Route::group(['prefix' => 'avm', 'as' => 'avm.'], function () {
            Route::get('/list', ['as' => 'list', 'uses' => 'Api\AVM\OrdersController@list']);
          });

          Route::group(['prefix' => 'tickets', 'as' => 'tickets.'], function () {
            Route::get('/list', ['as' => 'list', 'uses' => 'Api\Tickets\TicketsController@list']);
            Route::get('/view', ['as' => 'view', 'uses' => 'Api\Tickets\TicketsController@view']);
          });
        });
    });

    Route::group(['prefix' => 'surveys'], function () {
      Route::get(
          '{survey_id}/prepare/{order_id?}',
          ['as' => 'dashboard.surveys.prepare', 'uses' => 'SurveysController@prepare']
      );
      Route::post(
          '{survey_id}/submit/{order_id?}',
          ['as' => 'dashboard.surveys.submit', 'uses' => 'SurveysController@submit']
      );
    });

    Route::any('{wildcard}', ['uses' => 'DashboardController@index'])->where('wildcard', config('urls.ignoredRoutes'));
});
