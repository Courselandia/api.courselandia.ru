<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:'.Role::ADMIN->value.','.Role::MANAGER->value
    ],
    'prefix' => 'private/admin/alert/',
    'as' => 'api.private.admin.alert'
],
    function () {
        Route::get('read/', 'Admin\AlertController@read')
            ->name('read');

        Route::put('status/{id}', 'Admin\AlertController@status')
            ->name('status');

        Route::delete('destroy/', 'Admin\AlertController@destroy')
            ->name('destroy');
    });
