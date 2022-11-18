<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value
    ],
    'prefix' => 'private/admin/user/',
    'as' => 'api.private.admin.user',
], function () {
    Route::group([],
        function () {
            Route::get('read/', 'Admin\UserController@read')
                ->name('read');

            Route::get('get/{id}', 'Admin\UserController@get')
                ->name('get');

            Route::post('create/', 'Admin\UserController@create')
                ->name('create');

            Route::put('update/{id}', 'Admin\UserController@update')
                ->name('update');

            Route::put('update/status/{id}', 'Admin\UserController@updateStatus')
                ->name('update.status');

            Route::put('password/{id}', 'Admin\UserController@password')
                ->name('password');

            Route::delete('destroy/', 'Admin\UserController@destroy')
                ->name('destroy');
        });

    Route::group([
        'prefix' => 'profile/',
        'as' => '.profile'
    ],
        function () {
            Route::put('update', 'Admin\UserProfileController@update')
                ->name('update');

            Route::put('password', 'Admin\UserProfileController@password')
                ->name('password');

            Route::delete('image/destroy', 'Admin\UserProfileController@destroyImage')
                ->name('image.destroy');

            Route::put('image/update', 'Admin\UserProfileController@updateImage')
                ->name('image.update');
        });

    Route::group([
        'prefix' => 'config/',
        'as' => '.config'
    ],
        function () {
            Route::get('get', 'Admin\UserConfigController@get')
                ->name('get');

            Route::put('update', 'Admin\UserConfigController@update')
                ->name('update');
        });

    Route::group([
        'prefix' => 'image/',
        'as' => '.image'
    ],
        function () {
            Route::put('update/{id}', 'Admin\UserImageController@update')
                ->name('update');

            Route::delete('destroy/{id}', 'Admin\UserImageController@destroy')
                ->name('destroy');
        });
});
