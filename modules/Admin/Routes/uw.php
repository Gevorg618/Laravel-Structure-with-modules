<?php

Route::group(['prefix' => 'under-writing', 'namespace' => 'UW'], function () {
  Route::group(['prefix' => 'checklist'], function () {
      Route::get(
          '/',
          ['as' => 'admin.appraisal.under-writing.checklist', 'uses' => 'ChecklistController@index']
      );
      Route::any('/category/create/{category?}', [
          'as' => 'admin.appraisal.under-writing.checklist.category.create',
          'uses' => 'ChecklistController@createUwCategory'
      ]);
      Route::put('/category/update/{category}', [
          'as' => 'admin.appraisal.under-writing.checklist.category.update',
          'uses' => 'ChecklistController@updateUwCategory'
      ]);
      Route::get('/category/active-inactive/{category}', [
          'as' => 'admin.appraisal.under-writing.checklist.category.active-inactive',
          'uses' => 'ChecklistController@categoryMakeActiveInactive'
      ]);
      Route::get('/category/delete/{category}', [
          'as' => 'admin.appraisal.under-writing.checklist.category.delete',
          'uses' => 'ChecklistController@deleteCategory'
      ]);
      Route::any('/question/create/{question?}', [
          'as' => 'admin.appraisal.under-writing.checklist.question.create',
          'uses' => 'ChecklistController@createQuestion'
      ]);
      Route::get('/question/active-inactive/{question}', [
          'as' => 'admin.appraisal.under-writing.checklist.question.active-inactive',
          'uses' => 'ChecklistController@createQuestion'
      ]);
      Route::put('/question/update/{question}', [
          'as' => 'admin.appraisal.under-writing.checklist.question.update',
          'uses' => 'ChecklistController@updateQuestion'
      ]);
      Route::get('/question/delete/{question}', [
          'as' => 'admin.appraisal.under-writing.checklist.question.delete',
          'uses' => 'ChecklistController@deleteQuestion'
      ]);
  });
});