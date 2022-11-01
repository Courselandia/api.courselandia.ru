<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/salary/',
    'as' => 'api.private.admin.salary'
],
    function () {
        Route::get('read/', 'Admin\SalaryController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\SalaryController@get')
            ->name('get');

        Route::post('create/', 'Admin\SalaryController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\SalaryController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\SalaryController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\SalaryController@destroy')
            ->name('destroy');
    });
