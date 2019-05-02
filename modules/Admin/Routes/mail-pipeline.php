<?php

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