<?php

Route::group([
    'middleware' => ['locale', 'ajax'],
    'prefix' => 'private/access/',
    'as' => 'api.private.access'
], function () {
    Route::get('gate', 'AccessController@gate')->middleware('auth.api')->name('gate');
    Route::post('logout', 'AccessController@logout')->middleware('auth.api')->name('logout');
});

Route::group([
    'middleware' => ['locale', 'ajax'],
    'prefix' => 'private/site/access/',
    'as' => 'api.private.site.access'
], function () {
    Route::post('social', 'Site\AccessController@social')->name('social');
    Route::post('sign-up', 'Site\AccessController@signUp')->name('signUp');
    Route::post('sign-in', 'Site\AccessController@signIn')->name('signIn');
    Route::post('verify', 'Site\AccessController@verify')->middleware('auth.api', 'auth.user')->name('verify');
    Route::post('verify/{id}', 'Site\AccessController@toVerify')->name('toVerify');
    Route::post('forget', 'Site\AccessController@forget')->name('forget');
    Route::get('reset-check/{id}', 'Site\AccessController@resetCheck')->name('resetCheck');
    Route::post('reset/{id}', 'Site\AccessController@reset')->name('reset');
    Route::put('update', 'Site\AccessController@update')->middleware('auth.api', 'auth.user')->name('update');
    Route::put('password', 'Site\AccessController@password')->middleware('auth.api', 'auth.user')->name('password');
});

Route::group([
    'middleware' => ['locale', 'ajax'],
], function () {
    Route::post('token', 'AccessApiController@token')->name('token');
    Route::post('refresh', 'AccessApiController@refresh')->name('refresh');
});
