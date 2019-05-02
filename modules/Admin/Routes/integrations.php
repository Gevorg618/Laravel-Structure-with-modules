<?php

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
      Route::get('/search_email', [ 'as' => 'admin.integrations.google.search_email', 'uses' => 'GoogleAPIController@searchEmail' ]);
      Route::get('/view_email_message', [ 'as' => 'admin.integrations.google.view_email_message', 'uses' => 'GoogleAPIController@viewEmailMessage']);
      Route::get('/oauth_callback', ['as' => 'admin.integrations.google.oauth_callback','uses' => 'GoogleAPIController@oauthCallback']);
      Route::get('/revoke', [ 'as' => 'admin.integrations.google.revoke','uses' => 'GoogleAPIController@revoke']);
      Route::get('/refresh', ['as' => 'admin.integrations.google.refresh','uses' => 'GoogleAPIController@refresh']);
  });

  // Ditech manager
  Route::group(['prefix' => 'ditech', 'namespace' => 'Ditech'], function () {
      Route::get('/', 'DitechController@index')->name('admin.reports.ditech.index');
      Route::post('/download', 'DitechController@download')->name('admin.reports.ditech.download');
  });
});