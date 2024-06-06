<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/widget/',
    'as' => 'api.private.admin.widget'
],
    function () {
        Route::get('read/', 'Admin\WidgetController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\WidgetController@get')
            ->name('get');

        Route::put('update/{id}', 'Admin\WidgetController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\WidgetController@updateStatus')
            ->name('update.status');
    }
);
