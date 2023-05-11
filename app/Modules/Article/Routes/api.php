<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/article/',
    'as' => 'api.private.admin.article'
],
    function () {
        Route::get('read/', 'Admin\ArticleController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\ArticleController@get')
            ->name('get');

        Route::put('update/{id}', 'Admin\ArticleController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\ArticleController@updateStatus')
            ->name('update.status');

        Route::put('rewrite/{id}', 'Admin\ArticleController@rewrite')
            ->name('rewrite');
    }
);
