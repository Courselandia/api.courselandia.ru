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
        Route::post('request/', 'Admin\WriterController@request')
            ->name('request');

        Route::get('result/{id}', 'Admin\WriterController@result')
            ->name('result');
    });
