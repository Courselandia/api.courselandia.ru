<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/publication/',
    'as' => 'api.private.admin.publication'
],
    function () {
        Route::get('read/', 'Admin\PublicationController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\PublicationController@get')
            ->name('get');

        Route::post('create/', 'Admin\PublicationController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\PublicationController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\PublicationController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\PublicationController@destroy')
            ->name('destroy');

        Route::put('update/image/{id}', 'Admin\PublicationImageController@update')
            ->name('destroy');

        Route::delete('destroy/image/{id}', 'Admin\PublicationImageController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => ['locale', 'ajax'],
    'prefix' => 'private/site/publication/',
    'as' => 'api.private.site.publication'
],
    function () {
        Route::get('read/', 'Site\PublicationController@read')
            ->name('read');

        Route::get('get/', 'Site\PublicationController@get')
            ->name('get');
    });
