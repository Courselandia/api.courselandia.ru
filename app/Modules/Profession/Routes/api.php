<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/profession/',
    'as' => 'api.private.admin.profession'
],
    function () {
        Route::get('read/', 'Admin\ProfessionController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\ProfessionController@get')
            ->name('get');

        Route::post('create/', 'Admin\ProfessionController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\ProfessionController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\ProfessionController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\ProfessionController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/profession/',
    'as' => 'api.private.site.profession'
],
    function () {
        Route::get('get/{id}', 'Site\ProfessionController@get')
            ->name('get');
    }
);
