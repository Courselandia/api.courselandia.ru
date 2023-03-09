<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/tool/',
    'as' => 'api.private.admin.tool'
],
    function () {
        Route::get('read/', 'Admin\ToolController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\ToolController@get')
            ->name('get');

        Route::post('create/', 'Admin\ToolController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\ToolController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\ToolController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\ToolController@destroy')
            ->name('destroy');
    });

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/tool/',
    'as' => 'api.private.site.tool'
],
    function () {
        Route::get('get/{id}', 'Site\ToolController@get')
            ->name('get');

        Route::get('link/{link}', 'Site\ToolController@link')
            ->name('link');
    }
);
