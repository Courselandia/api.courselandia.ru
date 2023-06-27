<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/analyzer/',
    'as' => 'api.private.admin.analyzer'
],
    function () {
        Route::get('read/', 'Admin\AnalyzerController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\AnalyzerController@get')
            ->name('get');

        Route::put('analyze/{id}', 'Admin\AnalyzerController@analyze')
            ->name('analyze');
    }
);
