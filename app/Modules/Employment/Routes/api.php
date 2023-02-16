<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/employment/',
    'as' => 'api.private.admin.employment'
],
    function () {
        Route::get('read/', 'Admin\EmploymentController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\EmploymentController@get')
            ->name('get');

        Route::post('create/', 'Admin\EmploymentController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\EmploymentController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\EmploymentController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\EmploymentController@destroy')
            ->name('destroy');
    });
