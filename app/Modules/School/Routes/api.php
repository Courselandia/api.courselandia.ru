<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/school/',
    'as' => 'api.private.admin.school'
],
    function () {
        Route::get('read/', 'Admin\SchoolController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\SchoolController@get')
            ->name('get');

        Route::post('create/', 'Admin\SchoolController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\SchoolController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\SchoolController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\SchoolController@destroy')
            ->name('destroy');

        Route::put('update/image/{id}', 'Admin\SchoolImageController@update')
            ->name('destroy');

        Route::delete('destroy/image/{id}', 'Admin\SchoolImageController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/school/',
    'as' => 'api.private.site.school'
],
    function () {
        Route::get('read/', 'Site\SchoolController@read')
            ->name('read');

        Route::get('get/{id}', 'Site\SchoolController@get')
            ->name('get');
    });
