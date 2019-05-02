<?php

Route::group([], function () {
    Route::get('protect', ['as' => 'protect', 'uses' => 'Protect\ProtectController@protect']);
    Route::get('/', ['as' => 'index', 'uses' => 'FrontEnd\IndexController@index']);
    
    Route::post('subscribe', ['as' => 'subscribe', 'uses' => 'FrontEnd\SubscribeController@subscribe']);
    Route::post('/contact-us', ['as' => 'contactus', 'uses' => 'FrontEnd\ContactUsController@submit']);
    Route::group(['namespace' => 'FrontEnd'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'IndexController@index']);

        Route::group(['prefix' => 'news', 'as' => 'news.'], function () {
            Route::get('/{slug}-{row}', ['as' => 'view', 'uses' => 'NewsController@view']);
        });

        Route::get('/{uri}/{secondUri?}', ['as' => 'custom-pages', 'uses' => 'CustomPageController@index']);

    });

    Route::get('/home', 'HomeController@index');
    Route::get('logout', 'Auth\LoginController@logout');
    Route::group(['namespace' => 'FrontEnd', 'as' => 'frontend.'], function () {
        Route::get('/navigation', 'NavigationController@index');
        Route::get('/header-carousel', 'HeaderCarouselController@index');
        Route::get('/custom-pages', 'CustomPagesController@index');
    });
});
