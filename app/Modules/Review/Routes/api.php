<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/review/',
    'as' => 'api.private.admin.review'
],
    function () {
        Route::get('read/', 'Admin\ReviewController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\ReviewController@get')
            ->name('get');

        Route::post('create/', 'Admin\ReviewController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\ReviewController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\ReviewController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\ReviewController@destroy')
            ->name('destroy');
    });
