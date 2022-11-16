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

        Route::delete('destroy/', 'Admin\ReviewController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/review/',
    'as' => 'api.private.site.review'
],
    function () {
        Route::get('read/', 'Site\ReviewController@read')
            ->name('read');

        Route::get('break-down/{id}', 'Site\ReviewController@breakDown')
            ->name('breakDown');
    });
