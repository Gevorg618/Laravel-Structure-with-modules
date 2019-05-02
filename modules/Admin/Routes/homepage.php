<?php

Route::group(['as' => 'admin.announcement', 'prefix' => 'announcement'], function () {
    Route::get('get/{id}', ['as' => 'announcement.get', 'uses' => 'IndexController@getAnnouncement']);
});

Route::group(['as' => 'admin.calendar', 'prefix' => 'calendar'], function () {
    Route::get('load-events', ['as' => 'calendar.load-events', 'uses' => 'IndexController@loadEvents']);
    Route::get('view-event/{id}', ['as' => 'calendar.view-event', 'uses' => 'IndexController@viewEvent']);
    Route::get('add-event-form', ['as' => 'calendar.add-event-form', 'uses' => 'IndexController@addEventForm']);
    Route::get('edit-event-form/{id}', ['as' => 'calendar.edit-event-form', 'uses' => 'IndexController@editEventForm']);
    Route::post('add-event', ['as' => 'calendar.add-event', 'uses' => 'IndexController@addEvent']);
    Route::put('edit-event/{id}', ['as' => 'calendar.edit-event', 'uses' => 'IndexController@editEvent']);
    Route::delete('delete-event/{id}', ['as' => 'calendar.delete-event', 'uses' => 'IndexController@deleteEvent']);
});
