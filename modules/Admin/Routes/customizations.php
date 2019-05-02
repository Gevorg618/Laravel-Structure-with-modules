<?php

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
});
