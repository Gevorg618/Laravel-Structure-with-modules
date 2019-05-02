<?php

// Tickets
Route::group(['prefix' => 'ticket', 'namespace' => 'Ticket'], function () {

  // Ticket Status
  Route::group(['prefix' => 'statuses'], function () {
      Route::get(
          '/',
          ['as' => 'admin.ticket.statuses.index', 'uses' => 'TicketStatusesController@index']
      );
      Route::get(
          'data',
          ['as' => 'admin.ticket.statuses.data', 'uses' => 'TicketStatusesController@data']
      );
      Route::get(
          'create',
          ['as' => 'admin.ticket.statuses.create', 'uses' => 'TicketStatusesController@create']
      );
      Route::post(
          'store',
          ['as' => 'admin.ticket.statuses.store', 'uses' => 'TicketStatusesController@store']
      );
      Route::get(
          '{id}/edit',
          ['as' => 'admin.ticket.statuses.edit', 'uses' => 'TicketStatusesController@edit']
      );
      Route::patch(
          '{id}',
          ['as' => 'admin.ticket.statuses.update', 'uses' => 'TicketStatusesController@update']
      );
  });

  // Ticket Stats
  Route::group(['prefix' => 'stats'], function () {
      Route::get('ticket_stats', 'TicketStatsController@index')->name('admin.ticket.stats.index');
      Route::post('ticket_stats', 'TicketStatsController@postGetData')->name('admin.ticket.stats.data');
      Route::post('ticket_stats_export', 'TicketStatsController@excelExport')->name('admin.ticket.stats.export');
  });

  // Ticket Categories
  Route::group(['prefix' => 'categories'], function () {
      Route::get(
          '/',
          ['as' => 'admin.ticket.categories.index', 'uses' => 'CategoriesController@index']
      );
      Route::get(
          '/{id}/edit',
          ['as' => 'admin.ticket.categories.edit', 'uses' => 'CategoriesController@edit']
      );
      Route::put(
          '/{id}',
          ['as' => 'admin.ticket.categories.update', 'uses' => 'CategoriesController@update']
      );
      Route::delete(
           '/{id}',
          ['as' => 'admin.ticket.categories.delete', 'uses' => 'CategoriesController@destroy']
       );
      Route::get(
          'data',
          ['as' => 'admin.ticket.categories.data', 'uses' => 'CategoriesController@data']
      );
      Route::get(
          'create',
          ['as' => 'admin.ticket.categories.create', 'uses' => 'CategoriesController@create']
      );
      Route::post(
          'create',
          ['as' => 'admin.ticket.categories.store', 'uses' => 'CategoriesController@store']
      );
  });

  // Ticket Manager
  Route::group(['prefix' => 'manager'], function () {
      Route::get('/', ['as' => 'admin.ticket.manager', 'uses' => 'ManagerController@index']);
      // View ticket
      Route::any('view/{ticket}', [
          'as' => 'admin.ticket.manager.view',
          'uses' => 'ManagerController@view'
      ]);
      // View ticket content
      Route::get('view_ticket_content/{ticket}', [
          'as' => 'admin.ticket.manager.view_ticket_content',
          'uses' => 'ManagerController@viewTicketContent'
      ]);
      // Download Document
      Route::get('download_document/{file}', [
          'as' => 'admin.ticket.manager.download_document',
          'uses' => 'ManagerController@downloadDocument'
      ]);
      // View Image
      Route::get('view_image', [
          'as' => 'admin.ticket.manager.view_image',
          'uses' => 'ManagerController@viewImage'
      ]);
      // Get Tickets
      Route::get('get_tickets', [
          'as' => 'admin.ticket.manager.get_tickets',
          'uses' => 'ManagerController@getTickets'
      ]);
      // Get Activity Tab
      Route::get('get_activity_tab', [
          'as' => 'admin.ticket.manager.get_activity_tab',
          'uses' => 'ManagerController@getActivityTab'
      ]);
      // Get Stats Tab
      Route::get('get_stats_tab', [
          'as' => 'admin.ticket.manager.get_stats_tab',
          'uses' => 'ManagerController@getStatsTab'
      ]);
      // Get Currently Viewing Update
      Route::get('get_currently_viewing_update/{ticket}', [
          'as' => 'admin.ticket.manager.get_currently_viewing_update',
          'uses' => 'ManagerController@getCurrentlyViewingUpdate'
      ]);
      // Unlock Ticket
      Route::post('unlock_ticket', [
          'as' => 'admin.ticket.manager.unlock_ticket',
          'uses' => 'ManagerController@unlockTicket'
      ]);
      // Close Ticket
      Route::post('close_ticket/{ticket}', [
          'as' => 'admin.ticket.manager.close_ticket',
          'uses' => 'ManagerController@closeTicket'
      ]);
      // Open Ticket
      Route::post('open_ticket/{ticket}', [
          'as' => 'admin.ticket.manager.open_ticket',
          'uses' => 'ManagerController@openTicket'
      ]);
      // Get Email Template
      Route::get('get_email_template', [
          'as' => 'admin.ticket.manager.get_email_template',
          'uses' => 'ManagerController@getEmailTemplate'
      ]);
      // Get Comment Content
      Route::get('get_comment_content/{comment}', [
          'as' => 'admin.ticket.manager.get_comment_content',
          'uses' => 'ManagerController@getCommentContent'
      ]);
      // Get Ticket Comments
      Route::get('get_ticket_comments', [
          'as' => 'admin.ticket.manager.get_ticket_comments',
          'uses' => 'ManagerController@getTicketComments'
      ]);
      // Set Comment Visibility
      Route::post('set_comment_visibility/{comment}', [
          'as' => 'admin.ticket.manager.set_comment_visibility',
          'uses' => 'ManagerController@setCommentVisibility'
      ]);
      // Search Order
      Route::get('search_order', [
          'as' => 'admin.ticket.manager.search_order',
          'uses' => 'ManagerController@searchOrder'
      ]);
      // Remove Participant User
      Route::post('remove_participant_user', [
          'as' => 'admin.ticket.manager.remove_participant_user',
          'uses' => 'ManagerController@removeParticipantUser'
      ]);
      // Find Mentions
      Route::get('find_mentions', [
          'as' => 'admin.ticket.manager.find_mentions',
          'uses' => 'ManagerController@findMentions'
      ]);
      // Inline Category Edit
      Route::get('inline_category_edit', [
          'as' => 'admin.ticket.manager.inline_category_edit',
          'uses' => 'ManagerController@inlineCategoryEdit'
      ]);
      // Inline Status Edit
      Route::get('inline_status_edit', [
          'as' => 'admin.ticket.manager.inline_status_edit',
          'uses' => 'ManagerController@inlineStatusEdit'
      ]);
      // Inline Assign Edit
      Route::post('inline_assign_edit', [
          'as' => 'admin.ticket.manager.inline_assign_edit',
          'uses' => 'ManagerController@inlineAssignEdit'
      ]);
      // Get Multi Moderation Form
      Route::get('get_multi_moderation_form', [
          'as' => 'admin.ticket.manager.get_multi_moderation_form',
          'uses' => 'ManagerController@getMultiModerationForm'
      ]);
      // Process Multi Moderation Form
      Route::post('process_multi_moderation_form', [
          'as' => 'admin.ticket.manager.process_multi_moderation_form',
          'uses' => 'ManagerController@processMultiModerationForm'
      ]);
      // Get Multi Mod Record
      Route::get('get_multi_mod_record/{mod}', [
          'as' => 'admin.ticket.manager.get_multi_mod_record',
          'uses' => 'ManagerController@getMultiModRecord'
      ]);
      // Get Multi Mod Index Form
      Route::get('get_multi_mod_index_form/{mod}', [
          'as' => 'admin.ticket.manager.get_multi_mod_index_form',
          'uses' => 'ManagerController@getMultiModIndexForm'
      ]);
      // Apply Multi Mod Index
      Route::post('apply_multi_mod_index/{mod}', [
          'as' => 'admin.ticket.manager.apply_multi_mod_index',
          'uses' => 'ManagerController@applyMultiModIndex'
      ]);
  });

  // Ticket Rules
  Route::group(['prefix' => 'rule'], function () {
      Route::get('/', ['as' => 'admin.ticket.rule', 'uses' => 'RuleController@index']);
      // Data
      Route::get('data', [
          'as' => 'admin.ticket.rule.data',
          'uses' => 'RuleController@data'
      ]);
      // Create
      Route::any('create', [
          'as' => 'admin.ticket.rule.create',
          'uses' => 'RuleController@create'
      ]);
      // Update
      Route::any('update/{rule}', [
          'as' => 'admin.ticket.rule.update',
          'uses' => 'RuleController@update'
      ]);
      // Delete
      Route::get('delete/{rule}', [
          'as' => 'admin.ticket.rule.delete',
          'uses' => 'RuleController@delete'
      ]);
  });

  // Ticket Multi-Moderation
  Route::group(['prefix' => 'moderation'], function () {
      Route::get('/', ['as' => 'admin.ticket.moderation', 'uses' => 'ModerationController@index']);
      // Data
      Route::get('data', [
          'as' => 'admin.ticket.moderation.data',
          'uses' => 'ModerationController@data'
      ]);
      // Create
      Route::any('create', [
          'as' => 'admin.ticket.moderation.create',
          'uses' => 'ModerationController@create'
      ]);
      // Update
      Route::any('update/{mod}', [
          'as' => 'admin.ticket.moderation.update',
          'uses' => 'ModerationController@update'
      ]);
      // Delete
      Route::get('delete/{mod}', [
          'as' => 'admin.ticket.moderation.delete',
          'uses' => 'ModerationController@delete'
      ]);
  });
});