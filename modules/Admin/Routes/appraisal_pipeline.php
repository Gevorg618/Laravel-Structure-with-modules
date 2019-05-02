<?php

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