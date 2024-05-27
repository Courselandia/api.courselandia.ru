<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/promocode/',
    'as' => 'api.private.admin.promocode.'
],
    function () {
        Route::get('read/', 'Admin\PromocodeController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\PromocodeController@get')
            ->name('get');

        Route::post('create/', 'Admin\PromocodeController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\PromocodeController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\PromocodeController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\PromocodeController@destroy')
            ->name('destroy');
    }
);
