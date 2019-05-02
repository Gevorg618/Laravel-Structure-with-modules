<?php

Route::group(['prefix' => 'geo', 'namespace' => 'Geo'], function () {

  // AddressGeoCodeController
  Route::group(['prefix' => 'address'], function () {
      Route::get('/', ['as' => 'admin.geo.address', 'uses' => 'AddressGeoCodeController@index']);
      Route::get('data', ['as' => 'admin.geo.address.data', 'uses' => 'AddressGeoCodeController@geoCodeData']);
      Route::any('create/{addressGeoCode?}', ['as' => 'admin.geo.address.create', 'uses' => 'AddressGeoCodeController@geoCodeData']);
      Route::any('create_geo_code', ['as' => 'admin.geo.address.create_geo_code', 'uses' => 'AddressGeoCodeController@createGeoCode']);
      Route::get('refresh/{addressGeoCode}', ['as' => 'admin.geo.address.refresh', 'uses' => 'AddressGeoCodeController@refreshGeoCode']);
      Route::get('delete/{addressGeoCode}', ['as' => 'admin.geo.address.delete', 'uses' => 'AddressGeoCodeController@deleteGeoCode']);
  });

  Route::group(['prefix' => 'google-coding'], function () {
      Route::get('index', 'GoogleCodingsController@index')->name('admin.geo.google-coding.index');
      Route::get('data', 'GoogleCodingsController@data')->name('admin.geo.google-coding.data');
      Route::get('geo-code/{id}', 'GoogleCodingsController@geoCode')->name('admin.geo.google-coding.geo-code');
  });
});