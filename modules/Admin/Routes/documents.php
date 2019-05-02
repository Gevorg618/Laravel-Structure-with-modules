<?php

Route::group(['prefix' => 'document', 'namespace' => 'Documents'], function () {
  Route::group(['prefix' => 'types'], function () {
      Route::get('/', ['as' => 'admin.document.types', 'uses' => 'DocumentTypesController@index']);
      Route::get('data', ['as' => 'admin.document.types.data', 'uses' => 'DocumentTypesController@documentTypesData']);
      Route::any('create/{documentType?}', ['as' => 'admin.document.types.create', 'uses' => 'DocumentTypesController@createDocumentType']);
      Route::put('update/{documentType}', ['as' => 'admin.document.types.update', 'uses' => 'DocumentTypesController@updateDocumentType']);
      Route::get('delete/{documentType}', ['as' => 'admin.document.types.delete', 'uses' => 'DocumentTypesController@deleteDocumentType']);
  });

  Route::group(['prefix' => 'user'], function () {
      Route::group(['prefix' => 'types'], function () {
          Route::get('/', ['as' => 'admin.document.user.types', 'uses' => 'UserDocumentTypesController@index']);
          Route::get('data', ['as' => 'admin.document.user.types.data', 'uses' => 'UserDocumentTypesController@documentUserTypesData']);
          Route::any('create/{userDocumentType?}', ['as' => 'admin.document.user.types.create', 'uses' => 'UserDocumentTypesController@createUserDocumentType']);
          Route::put('update/{userDocumentType}', ['as' => 'admin.document.user.types.update', 'uses' => 'UserDocumentTypesController@updateUserDocumentType']);
          Route::get('delete/{userDocumentType}', ['as' => 'admin.document.user.types.delete', 'uses' => 'UserDocumentTypesController@deleteUserDocumentType']);
      });
  });

  Route::group(['prefix' => 'resource'], function () {
      Route::get('/', ['as' => 'admin.document.resource', 'uses' => 'ResourceDocumentController@index']);
      Route::get('data', ['as' => 'admin.document.resource.data', 'uses' => 'ResourceDocumentController@resourceData']);
      Route::any('create/{resource?}', ['as' => 'admin.document.resource.create', 'uses' => 'ResourceDocumentController@createResource']);
      Route::put('update/{resource}', ['as' => 'admin.document.resource.update', 'uses' => 'ResourceDocumentController@updateResource']);
      Route::get('delete/{resource}', ['as' => 'admin.document.resource.delete', 'uses' => 'ResourceDocumentController@deleteResource']);
  });

  // Upload Manager
  Route::group(['prefix' => 'upload'], function () {
      Route::get('/', ['as' => 'admin.document.upload', 'uses' => 'UploadController@index']);
      Route::get('data', ['as' => 'admin.document.upload.data', 'uses' => 'UploadController@uploadedData']);
      Route::post('upload', ['as' => 'admin.document.upload.upload', 'uses' => 'UploadController@uploadFile']);
      Route::get('delete/{file}', ['as' => 'admin.document.upload.delete', 'uses' => 'UploadController@deleteFile']);
      Route::get('update_status/{file}', ['as' => 'admin.document.upload.update_status', 'uses' => 'UploadController@updateStatus']);
  });

  // Global Documents
  Route::group(['prefix' => 'global'], function () {
      Route::get('index', 'GlobalDocumentsController@index')->name('admin.document.global.index');
      Route::get('create', 'GlobalDocumentsController@create')->name('admin.document.global.create');
      Route::post('store', 'GlobalDocumentsController@store')->name('admin.document.global.store');
      Route::get('data', 'GlobalDocumentsController@data')->name('admin.document.global.data');
      Route::get('delete/{id}', 'GlobalDocumentsController@delete')->name('admin.document.global.delete');
      Route::get('edit/{id}', 'GlobalDocumentsController@edit')->name('admin.document.global.edit');
      Route::put('update/{id}', 'GlobalDocumentsController@update')->name('admin.document.global.update');
  });
});