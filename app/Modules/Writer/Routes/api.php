<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/writer/',
    'as' => 'api.private.admin.writer'
],
    function () {
        Route::get('result/{id}', 'Admin\WriterController@result')
            ->name('result');

        Route::post('write/', 'Admin\WriterController@write')
            ->name('write');
    });
