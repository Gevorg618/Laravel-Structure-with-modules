<?php

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