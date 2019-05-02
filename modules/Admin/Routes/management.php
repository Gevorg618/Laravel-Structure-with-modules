<?php

Route::group(['prefix' => 'management', 'namespace' => 'Management'], function () {
    Route::group(['as' => 'admin.management.fha-licenses.', 'prefix' => 'management/fha-licenses'], function () {
      Route::get('data', ['as' => 'data', 'uses' => 'FhaLicensesController@data']);
      Route::get('/', ['as' => 'index', 'uses' => 'FhaLicensesController@index']);
      Route::get('show/{id}', ['as' => 'show', 'uses' => 'FhaLicensesController@show']);
    });

    // Admin Teams Manager
    Route::group(['prefix' => 'admin-teams-manager', 'namespace' => 'AdminTeamsManager'], function () {
        Route::get('/', ['as' => 'admin.management.admin-teams-manager', 'uses' => 'AdminTeamsManagerController@index']);
        Route::get('/data', ['as' => 'admin.management.admin-teams-manager.data', 'uses' => 'AdminTeamsManagerController@data']);
        Route::get('/create', ['as' => 'admin.management.admin-teams-manager.create', 'uses' => 'AdminTeamsManagerController@create']);
        Route::post('/create', ['as' => 'admin.management.admin-teams-manager.store', 'uses' => 'AdminTeamsManagerController@store']);
        Route::get('/edit/{id}', ['as' => 'admin.management.admin-teams-manager.edit', 'uses' => 'AdminTeamsManagerController@edit']);
        Route::put('/edit/{id}', ['as' => 'admin.management.admin-teams-manager.update', 'uses' => 'AdminTeamsManagerController@update']);
        Route::delete('/delete/{id}', ['as' => 'admin.management.admin-teams-manager.delete', 'uses' => 'AdminTeamsManagerController@destroy']);
    });

    //Wholesale Lenders
    Route::group(['prefix' => 'lenders', 'namespace' => 'WholesaleLenders'], function () {
        Route::get('/', ['as' => 'admin.management.lenders', 'uses' => 'LendersController@index']);
        Route::get('/data', ['as' => 'admin.management.lenders.data', 'uses' => 'LendersController@data']);
        Route::get('/create', ['as' => 'admin.management.lenders.create', 'uses' => 'LendersController@create']);
        Route::post('/store', ['as' => 'admin.management.lenders.store', 'uses' => 'LendersController@store']);
        Route::get('/edit/{id}', ['as' => 'admin.management.lenders.edit', 'uses' => 'LendersController@edit']);
        Route::get('/delete/{id}', ['as' => 'admin.management.lenders.delete', 'uses' => 'LendersController@delete']);
        Route::put('/update/{id}', ['as' => 'admin.management.lenders.update', 'uses' => 'LendersController@update']);
        Route::post('/import-excluded-users', ['as' => 'admin.management.lenders.import-excluded-users', 'uses' => 'LendersController@importExcludedUsers']);
        Route::post('/add-uw', ['as' => 'admin.management.lenders.add-uw', 'uses' => 'LendersController@addUW']);
        Route::post('/update-uw/{id}', ['as' => 'admin.management.lenders.update-uw', 'uses' => 'LendersController@updateUW']);
        Route::post('/delete-uw/{id}', ['as' => 'admin.management.lenders.delete-uw', 'uses' => 'LendersController@deleteUW']);
        Route::get('/get-client-names', ['as' => 'admin.management.lenders.get-client-names', 'uses' => 'LendersController@getClientNames']);
        Route::post('/add-user-manager', ['as' => 'admin.management.lenders.add-user-manager', 'uses' => 'LendersController@addUserManager']);
        Route::post('/delete-user-manager', ['as' => 'admin.management.lenders.delete-user-manager', 'uses' => 'LendersController@deleteUserManager']);
        Route::get('/get-appraiser-names', ['as' => 'admin.management.lenders.get-appraiser-names', 'uses' => 'LendersController@getAppraiserNames']);
        Route::post('/add-excluded-appraiser', ['as' => 'admin.management.lenders.add-excluded-appraiser', 'uses' => 'LendersController@addExcludedAppraiser']);
        Route::post('/delete-excluded-appraiser', ['as' => 'admin.management.lenders.delete-excluded-appraiser', 'uses' => 'LendersController@deleteExcludedAppraiser']);
        Route::get('/add-proposed/{id}', ['as' => 'admin.management.lenders.add-proposed', 'uses' => 'LendersController@addProposed']);
        Route::post('/add-proposed', ['as' => 'admin.management.lenders.create-proposed', 'uses' => 'LendersController@createProposed']);
        Route::get('/edit-proposed/{id}', ['as' => 'admin.management.lenders.edit-proposed', 'uses' => 'LendersController@editProposed']);
        Route::put('/update-proposed', ['as' => 'admin.management.lenders.update-proposed', 'uses' => 'LendersController@updateProposed']);
        Route::get('/download-template', ['as' => 'admin.management.lenders.download-template', 'uses' => 'LendersController@downloadTemplate']);
        Route::post('/add-user-note', ['as' => 'admin.management.lenders.add-user-note', 'uses' => 'LendersController@addUserNote']);
        Route::delete('/delete-proposed', ['as' => 'admin.management.lenders.delete-proposed', 'uses' => 'LendersController@deleteProposed']);
    });

    Route::group(['prefix' => 'lenders'], function () {
        Route::group(['prefix' => 'exclusionary'], function () {
            Route::get('/', ['as' => 'admin.lenders.exclusionary', 'uses' => 'ExclusionaryController@index']);
            Route::get('data', ['as' => 'admin.lenders.exclusionary.data', 'uses' => 'ExclusionaryController@exclusionaryData']);
            Route::get('licenses/data', ['as' => 'admin.lenders.licenses.data', 'uses' => 'ExclusionaryController@licensesData']);
        });
    });

    //QC checklist
    Route::group(['prefix' => 'appr_qc_checklist'], function () {
        Route::get('/', 'QCChecklistController@index')        ->name('admin.qc.checklist.index');
        Route::get('update_question_status/{question}', 'QCChecklistController@updateQuestionStatus')
          ->name('admin.qc.checklist.update_question_status');
        Route::get('delete/{question}', 'QCChecklistController@destroy')
          ->name('admin.qc.checklist.delete');
        Route::get('update_category_status/{category}', 'QCChecklistController@updateCategoryStatus')
          ->name('admin.qc.checklist.update_category_status');
        Route::get('edit/{question}', 'QCChecklistController@edit')
          ->name('admin.qc.checklist.edit');
        Route::put('update/{question}', 'QCChecklistController@update')
          ->name('admin.qc.checklist.update');
        Route::post('/', 'QCChecklistController@store')
          ->name('admin.qc.checklist.store');
        Route::get('create', 'QCChecklistController@create')
          ->name('admin.qc.checklist.create');
        Route::post('parent_questions_for_category/{id}', 'QCChecklistController@getParentQuestions')
          ->name('admin.qc.checklist.parent_questions');
        Route::get('category/edit/{category}', 'QCChecklistController@editCategory')
          ->name('admin.qc.checklist.edit_category');
        Route::put('category/{category}', 'QCChecklistController@updateCategory')
          ->name('admin.qc.checklist.update_category');
        Route::get('category/create', 'QCChecklistController@createCategory')
          ->name('admin.qc.checklist.create_category');
        Route::post('category', 'QCChecklistController@storeCategory')
          ->name('admin.qc.checklist.store_category');
        Route::post('sort_questions', 'QCChecklistController@sortQuestions')
          ->name('admin.qc.checklist.sort_questions');
        Route::get('client/{client}/change_activity/{activity}', 'QCChecklistController@changeActivityByClient')
          ->name('admin.qc.checklist.client.change_activity');
        Route::get('lender/{lender}/change_activity/{activity}', 'QCChecklistController@changeActivityByLender')
          ->name('admin.qc.checklist.lender.change_activity');
        Route::post('clients_data', 'QCChecklistController@clientsData')
          ->name('admin.qc.checklist.clients_data');
        Route::post('lenders_data', 'QCChecklistController@lendersData')
          ->name('admin.qc.checklist.lenders_data');
    });

    // QC data collection
    Route::group(['prefix' => 'appr_qc_data_collection'], function () {
        Route::get('/', 'DataCollectionController@index')->name('admin.qc.collection.index');
        Route::get('create', 'DataCollectionController@create')->name('admin.qc.collection.create');
        Route::get('data', 'DataCollectionController@data')->name('admin.qc.collection.data');
        Route::get('edit/{row}', 'DataCollectionController@edit')->name('admin.qc.collection.edit');
        Route::get('delete/{question}', 'DataCollectionController@destroy')->name('admin.qc.collection.delete');
        Route::put('update/{question}', 'DataCollectionController@update')->name('admin.qc.collection.update');
        Route::post('/', 'DataCollectionController@store')->name('admin.qc.collection.store');
    });

    // ASC LIcenses
    Route::group(['prefix' => 'asc-licenses'], function () {
        Route::get('/', ['as' => 'admin.management.asc-licenses', 'uses' => 'ASCLicensesController@index']);
        Route::get('data', ['as' => 'admin.management.asc-licenses.data', 'uses' => 'ASCLicensesController@data']);
    });

    Route::group(['prefix' => 'email-templates'], function () {
        Route::get('/', ['as' => 'admin.management.email-templates', 'uses' => 'EmailTemplatesController@index']);
        Route::get('data', ['as' => 'admin.management.email-templates.data','uses' => 'EmailTemplatesController@emailTemplatesData']);
        Route::any(
          'create/{emailTemplate?}',
          [
              'as' => 'admin.management.email-templates.create',
              'uses' => 'EmailTemplatesController@createEmailTemplate'
          ]
      );
        Route::put(
          'update/{emailTemplate}',
          [
              'as' => 'admin.management.email-templates.update',
              'uses' => 'EmailTemplatesController@updateEmailTemplate'
          ]
      );
        Route::get(
          'delete/{emailTemplate}',
          [
              'as' => 'admin.management.email-templates.delete',
              'uses' => 'EmailTemplatesController@deleteEmailTemplate'
          ]
      );
    });

    Route::group(['prefix' => 'user-templates'], function () {
        Route::get('/', ['as' => 'admin.management.user-templates', 'uses' => 'UserTemplatesController@index']);
        Route::get(
          'data',
          [
              'as' => 'admin.management.user-templates.data',
              'uses' => 'UserTemplatesController@userTemplatesData'
          ]
      );
        Route::any(
          'create/{userTemplate?}',
          [
              'as' => 'admin.management.user-templates.create',
              'uses' => 'UserTemplatesController@createUserTemplate'
          ]
      );
        Route::put(
          'update/{userTemplate}',
          [
              'as' => 'admin.management.user-templates.update',
              'uses' => 'UserTemplatesController@updateUserTemplate'
          ]
      );
        Route::get(
          'delete/{userTemplate}',
          [
              'as' => 'admin.management.user-templates.delete',
              'uses' => 'UserTemplatesController@deleteUserTemplate'
          ]
      );
    });

    Route::group(['prefix' => 'zipcodes'], function () {
        Route::get(
          '/',
          ['as' => 'admin.management.zipcodes', 'uses' => 'ZipCodesController@index']
      );
        Route::get(
          'data',
          ['as' => 'admin.management.zipcodes.data', 'uses' => 'ZipCodesController@zipCodeData']
      );
        Route::any(
          'create/{ZipCode?}',
          ['as' => 'admin.management.zipcodes.create', 'uses' => 'ZipCodesController@createZipCode']
      );
        Route::put(
          'update/{ZipCode}',
          ['as' => 'admin.management.zipcodes.update', 'uses' => 'ZipCodesController@updateZipCode']
      );
        Route::get(
          'delete/{ZipCode}',
          ['as' => 'admin.management.zipcodes.delete', 'uses' => 'ZipCodesController@deleteZipCode']
      );
    });

    Route::group(['prefix' => 'custom-email-templates'], function () {
        Route::get(
          '/',
          ['as' => 'admin.management.custom-email-templates', 'uses' => 'CustomEmailTemplatesController@index']
      );
        Route::get(
          'data',
          [
              'as' => 'admin.management.custom-email-templates.data',
              'uses' => 'CustomEmailTemplatesController@customEmailTemplatesData'
          ]
      );
        Route::any(
          'create/{customEmailTemplate?}',
          [
              'as' => 'admin.management.custom-email-templates.create',
              'uses' => 'CustomEmailTemplatesController@createCustomEmailTemplate'
          ]
      );
        Route::put(
          'update/{customEmailTemplate}',
          [
              'as' => 'admin.management.custom-email-templates.update',
              'uses' => 'CustomEmailTemplatesController@updateCustomEmailTemplate'
          ]
      );
        Route::get(
          'delete/{customEmailTemplate}',
          [
              'as' => 'admin.management.custom-email-templates.delete',
              'uses' => 'CustomEmailTemplatesController@deleteCustomEmailTemplate'
          ]
      );
    });
    Route::group(['prefix' => 'groups'], function () {
        Route::get(
          '/',
          ['as' => 'admin.management.groups.index', 'uses' => 'GroupsController@index']
      );
        Route::get(
          'data',
          ['as' => 'admin.management.groups.data', 'uses' => 'GroupsController@data']
      );
        Route::get(
          'create',
          ['as' => 'admin.management.groups.create', 'uses' => 'GroupsController@create']
      );
        Route::post(
          'store',
          ['as' => 'admin.management.groups.store', 'uses' => 'GroupsController@store']
      );
        Route::get(
          '{id}/edit',
          ['as' => 'admin.management.groups.edit', 'uses' => 'GroupsController@edit']
      );
        Route::patch(
          '{id}',
          ['as' => 'admin.management.groups.update', 'uses' => 'GroupsController@update']
      );
        Route::get(
          '{group}',
          ['as' => 'admin.management.groups.delete', 'uses' => 'GroupsController@delete']
      );
    });

    //Admin Groups
    Route::group(['prefix' => 'admin-groups'], function () {
        Route::get('/', ['as' => 'admin.management.admin-groups', 'uses' => 'AdminGroupController@index']);
        Route::get('data', ['as' => 'admin.management.admin-groups.data', 'uses' => 'AdminGroupController@data']);
        Route::get('/create', ['as' => 'admin.management.admin-groups.create', 'uses' => 'AdminGroupController@create']);
        Route::post('/create', ['as' => 'admin.management.admin-groups.store', 'uses' => 'AdminGroupController@store']);
        Route::get('{id}/edit', ['as' => 'admin.management.admin-groups.edit', 'uses' => 'AdminGroupController@edit']);
        Route::put('/update/{id}', ['as' => 'admin.management.admin-groups.update', 'uses' => 'AdminGroupController@update']);
        Route::post('/delete', ['as' => 'admin.management.admin-groups.delete', 'uses' => 'AdminGroupController@destroy']);
        Route::get('/clear-cache', ['as' => 'admin.management.admin-groups.clear-cache', 'uses' => 'AdminGroupController@clearCache']);
    });

    //Active Users
    Route::group(['prefix' => 'appraiser-groups', 'namespace' => 'ActiveUsers'], function () {
        Route::get('/', ['as' => 'admin.management.active-users', 'uses' => 'ActiveUsersController@index']);
    });

    // AppraiserGroupsController
    Route::group(['prefix' => 'appraiser-groups'], function () {
        Route::get('index', 'AppraiserGroupsController@index')->name('admin.management.appraiser.index');
        Route::get('data', 'AppraiserGroupsController@data')->name('admin.management.appraiser.data');
        Route::get('create', 'AppraiserGroupsController@create')->name('admin.management.appraiser.create');
        Route::post('store', 'AppraiserGroupsController@store')->name('admin.management.appraiser.store');
        Route::get('managers', 'AppraiserGroupsController@getManagers')->name('admin.management.appraiser.managers');
        Route::get('appraisers', 'AppraiserGroupsController@getAppraisers')->name('admin.management.appraiser.appraisers');
        Route::post('appraisers', 'AppraiserGroupsController@storeAppraiser')->name('admin.management.appraiser.appraisers.store');
        Route::delete('appraisers', 'AppraiserGroupsController@destroyAppraiser')->name('admin.management.appraiser.appraisers.destroy');
        Route::get('edit/{id}', 'AppraiserGroupsController@edit')->name('admin.management.appraiser.edit');
        Route::put('{id}', 'AppraiserGroupsController@update')->name('admin.management.appraiser.update');
    });

    Route::group(['prefix' => 'announcements'], function () {
        Route::get('/', ['as' => 'admin.management.announcements', 'uses' => 'AnnouncementsController@index']);
        Route::get(
          'data',
          ['as' => 'admin.management.announcements.data', 'uses' => 'AnnouncementsController@announcementsData']
      );
        Route::post(
          'user-types',
          [
              'as' => 'admin.management.announcements.user-types',
              'uses' => 'AnnouncementsController@userTypesData'
          ]
      );
        Route::post(
          'viewed',
          ['as' => 'admin.management.announcements.viewed', 'uses' => 'AnnouncementsController@viewedData']
      );
        Route::any(
          'create/{announcement?}',
          [
              'as' => 'admin.management.announcements.create',
              'uses' => 'AnnouncementsController@createAnnouncement'
          ]
      );
        Route::put(
          'update/{announcement}',
          [
              'as' => 'admin.management.announcements.update',
              'uses' => 'AnnouncementsController@updateAnnouncement'
          ]
      );
        Route::get(
          'delete/{announcement}',
          [
              'as' => 'admin.management.announcements.delete',
              'uses' => 'AnnouncementsController@deleteAnnouncement'
          ]
      );
    });

    Route::group(['prefix' => 'surveys'], function () {
        Route::get(
          '/',
          ['as' => 'admin.management.surveys.index', 'uses' => 'SurveysController@index']
      );
        Route::get(
          'data',
          ['as' => 'admin.management.surveys.data', 'uses' => 'SurveysController@data']
      );
        Route::get(
          'create',
          ['as' => 'admin.management.surveys.create', 'uses' => 'SurveysController@create']
      );
        Route::post(
          'store',
          ['as' => 'admin.management.surveys.store', 'uses' => 'SurveysController@store']
      );
        Route::get(
          '{id}/edit',
          ['as' => 'admin.management.surveys.edit', 'uses' => 'SurveysController@edit']
      );
        Route::get(
          '{survey}/questions',
          ['as' => 'admin.management.surveys.show.questions', 'uses' => 'SurveysController@questions']
      );
        Route::patch(
          '{id}',
          ['as' => 'admin.management.surveys.update', 'uses' => 'SurveysController@update']
      );
        Route::get(
          '{survey}',
          ['as' => 'admin.management.surveys.delete', 'uses' => 'SurveysController@delete']
      );

        /**QUESTIONS From Survey page**/
        Route::group(['prefix' => 'questions'], function () {
            Route::get(
              'data/{id?}',
              ['as' => 'admin.management.surveys.questions.data', 'uses' => 'SurveyQuestionsController@data']
          );
            Route::get(
              'create/{id?}',
              [
                  'as' => 'admin.management.surveys.questions.create',
                  'uses' => 'SurveyQuestionsController@create'
              ]
          );
            Route::post(
              'store',
              ['as' => 'admin.management.surveys.questions.store', 'uses' => 'SurveyQuestionsController@store']
          );
            Route::get(
              '{id}/edit',
              ['as' => 'admin.management.surveys.questions.edit', 'uses' => 'SurveyQuestionsController@edit']
          );
            Route::patch(
              '{id}',
              [
                  'as' => 'admin.management.surveys.questions.update',
                  'uses' => 'SurveyQuestionsController@update'
              ]
          );
            Route::get(
              '{question}',
              [
                  'as' => 'admin.management.surveys.questions.delete',
                  'uses' => 'SurveyQuestionsController@delete'
              ]
          );
        });
    });

    Route::group(['prefix' => 'survey-report'], function () {
        Route::get(
          '/',
          ['as' => 'admin.management.surveys.answers.index', 'uses' => 'SurveyAnswersController@index']
      );
        Route::get(
          'data',
          ['as' => 'admin.management.surveys.answers.data', 'uses' => 'SurveyAnswersController@data']
      );
        Route::get(
          '{id}/answers',
          ['as' => 'admin.management.surveys.answers.show', 'uses' => 'SurveyAnswersController@show']
      );
        Route::get(
          '{survey_id}/answers/data',
          [
              'as' => 'admin.management.surveys.answers.show.data',
              'uses' => 'SurveyAnswersController@answersData'
          ]
      );
        Route::post(
          'answers/report',
          ['as' => 'admin.management.surveys.answers.report', 'uses' => 'SurveyAnswersController@report']
      );
    });
});
