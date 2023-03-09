<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/direction/',
    'as' => 'api.private.admin.direction'
],
    function () {
        Route::get('read/', 'Admin\DirectionController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\DirectionController@get')
            ->name('get');

        Route::post('create/', 'Admin\DirectionController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\DirectionController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\DirectionController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\DirectionController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/direction/',
    'as' => 'api.private.site.direction'
],
    function () {
        Route::get('get/{id}', 'Site\DirectionController@get')
            ->name('get');

        Route::get('link/{link}', 'Site\DirectionController@link')
            ->name('link');
    }
);
