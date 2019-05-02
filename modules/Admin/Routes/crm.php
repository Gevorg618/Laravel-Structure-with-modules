<?php


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
