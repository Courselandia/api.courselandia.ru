<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/category/',
    'as' => 'api.private.admin.category'
],
    function () {
        Route::get('read/', 'Admin\CategoryController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\CategoryController@get')
            ->name('get');

        Route::post('create/', 'Admin\CategoryController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\CategoryController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\CategoryController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\CategoryController@destroy')
            ->name('destroy');
    }
);

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/category/',
    'as' => 'api.private.site.category'
],
    function () {
        Route::get('get/{id}', 'Site\CategoryController@get')
            ->name('get');
    }
);
