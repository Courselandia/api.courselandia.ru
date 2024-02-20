<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/section/',
    'as' => 'api.private.admin.section'
],
    function () {
        Route::get('read/', 'Admin\SectionController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\SectionController@get')
            ->name('get');

        Route::post('create/', 'Admin\SectionController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\SectionController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\SectionController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\SectionController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/section/',
    'as' => 'api.private.site.section'
],
    function () {
        Route::get('link', 'Site\SectionController@link')
            ->name('link');
    }
);
