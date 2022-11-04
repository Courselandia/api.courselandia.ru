<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/faq/',
    'as' => 'api.private.admin.faq'
],
    function () {
        Route::get('read/', 'Admin\FaqController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\FaqController@get')
            ->name('get');

        Route::post('create/', 'Admin\FaqController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\FaqController@update')
            ->name('update');

        Route::delete('destroy/', 'Admin\FaqController@destroy')
            ->name('destroy');

        Route::put('update/status/{id}', 'Admin\FaqController@updateStatus')
            ->name('update.status');
    });
