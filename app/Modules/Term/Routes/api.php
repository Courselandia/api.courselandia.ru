<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value,
    ],
    'prefix' => 'private/admin/term/',
    'as' => 'api.private.admin.term',
],
    function () {
        Route::get('read/', 'Admin\TermController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\TermController@get')
            ->name('get');

        Route::post('create/', 'Admin\TermController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\TermController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\TermController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\TermController@destroy')
            ->name('destroy');
    }
);
