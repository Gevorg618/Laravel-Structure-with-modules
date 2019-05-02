<?php

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