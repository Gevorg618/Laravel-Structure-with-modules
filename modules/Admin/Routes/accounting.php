<?php

// Accounting
Route::group(['prefix' => 'accounting', 'namespace' => 'Accounting'], function () {

  // AP Calendar
  Route::group(['prefix' => 'ap-calendar'], function () {
    Route::get('/', 'APCalendarController@index')->name('admin.ap_calendar.index');
    Route::get('load', 'APCalendarController@load')->name('admin.ap_calendar.load');
});

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