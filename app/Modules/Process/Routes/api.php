<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/process/',
    'as' => 'api.private.admin.process'
],
    function () {
        Route::get('read/', 'Admin\ProcessController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\ProcessController@get')
            ->name('get');

        Route::post('create/', 'Admin\ProcessController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\ProcessController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\ProcessController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\ProcessController@destroy')
            ->name('destroy');
    });
