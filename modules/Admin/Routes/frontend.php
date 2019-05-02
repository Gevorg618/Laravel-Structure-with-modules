<?php

Route::group(['prefix' => 'frontend-site', 'namespace' => 'FrontEnd', 'as' => 'admin.frontend-site.'], function () {
  Route::group(['prefix' => 'header-carousel', 'as' => 'header-carousel.'], function () {
      Route::get('/', ['as' => 'index','uses' => 'HeaderCarouselController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'HeaderCarouselController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'HeaderCarouselController@create']);
      Route::get('/edit/{carousel?}', ['as' => 'edit', 'uses' => 'HeaderCarouselController@edit']);
      Route::put('/update/{carousel?}', ['as' => 'update', 'uses' => 'HeaderCarouselController@update']);
      Route::get('/delete/{carousel}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'HeaderCarouselController@destroy']);
  });

  Route::group(['prefix' => 'latest-news', 'as' => 'latest-news.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'LatestNewsController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'LatestNewsController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'LatestNewsController@create']);
      Route::any('/edit/{latestNews?}', ['as' => 'edit', 'uses' => 'LatestNewsController@edit']);
      Route::put('/update/{latestNews}', ['as' => 'update', 'uses' => 'LatestNewsController@update']);
      Route::get('/delete/{latestNews}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'LatestNewsController@destroy']);
  });
  Route::group(['prefix' => 'client-testimonials', 'as' => 'client-testimonials.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'ClientTestimonialController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'ClientTestimonialController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'ClientTestimonialController@create']);
      Route::any('/edit/{testimonial?}', ['as' => 'edit', 'uses' => 'ClientTestimonialController@edit']);
      Route::put('/update/{testimonial}', ['as' => 'update', 'uses' => 'ClientTestimonialController@update']);
      Route::get('/delete/{testimonial}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'ClientTestimonialController@destroy']);
  });

  Route::group(['prefix' => 'team-member', 'as' => 'team-member.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'TeamMemberController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'TeamMemberController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'TeamMemberController@create']);
      Route::any('/edit/{member?}', ['as' => 'edit', 'uses' => 'TeamMemberController@edit']);
      Route::put('/update/{member}', ['as' => 'update', 'uses' => 'TeamMemberController@update']);
      Route::get('/delete/{member}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'TeamMemberController@destroy']);
  });

  Route::group(['prefix' => 'services', 'as' => 'services.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'ServicesController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'ServicesController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'ServicesController@create']);
      Route::any('/edit/{serviceProvide?}', ['as' => 'edit', 'uses' => 'ServicesController@create']);
      Route::put('/update/{serviceProvide}', ['as' => 'update', 'uses' => 'ServicesController@update']);
      Route::get('/delete/{serviceProvide}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'ServicesController@destroy']);
  });

  Route::group(['prefix' => 'custom-pages'], function () {
      Route::get('/', ['as' => 'admin.front-end.custom-pages', 'uses' => 'CustomPagesController@index']);
  });

  Route::group(['prefix' => 'stats', 'as' => 'stats.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'StatsController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'StatsController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'StatsController@create']);
      Route::any('/edit/{stat?}', ['as' => 'edit', 'uses' => 'StatsController@create']);
      Route::put('/update/{stat}', ['as' => 'update', 'uses' => 'StatsController@update']);
      Route::get('/delete/{stat}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'StatsController@destroy']);
  });

  Route::group(['prefix' => 'navigation-menu', 'as' => 'navigation-menu.'], function () {
      Route::get('/', ['as' => 'index', 'uses' => 'NavigationController@index']);
      Route::get('/data', ['as' => 'data', 'uses' => 'NavigationController@data']);
      Route::any('/create', ['as' => 'create', 'uses' => 'NavigationController@create']);
      Route::any('/edit/{navigationMenu?}', ['as' => 'edit', 'uses' => 'NavigationController@create']);
      Route::put('/update/{navigationMenu}', ['as' => 'update', 'uses' => 'NavigationController@update']);
      Route::get('/delete/{navigationMenu}', ['middleware' => ['csrf.get'], 'as' => 'delete', 'uses' => 'NavigationController@destroy']);
  });
});