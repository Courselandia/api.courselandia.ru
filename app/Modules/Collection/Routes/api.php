<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/collection/',
    'as' => 'api.private.admin.collection'
],
    function () {
        Route::get('read/', 'Admin\CollectionController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\CollectionController@get')
            ->name('get');

        Route::post('create/', 'Admin\CollectionController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\CollectionController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\CollectionController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\CollectionController@destroy')
            ->name('destroy');

        Route::put('update/image/{id}', 'Admin\CollectionImageController@update')
            ->name('update.image');

        Route::delete('destroy/image/{id}', 'Admin\CollectionImageController@destroy')
            ->name('destroy.image');
    });
