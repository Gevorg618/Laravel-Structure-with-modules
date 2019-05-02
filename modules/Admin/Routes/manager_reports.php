<?php

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