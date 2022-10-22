<?php

Route::group([
    'middleware' => ['locale', 'ajax'],
    'prefix' => 'private/location/',
    'as' => 'api.private.location'
], function () {
    Route::get('countries/', 'LocationController@countries')->name('countries');
    Route::get('regions/{country}', 'LocationController@regions')->name('regions');
});
