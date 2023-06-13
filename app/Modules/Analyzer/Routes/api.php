<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/analyze/',
    'as' => 'api.private.admin.analyze'
],
    function () {
        Route::get('read/', 'Admin\AnalyzeController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\AnalyzeController@get')
            ->name('get');

        Route::put('analyze/{id}', 'Admin\AnalyzeController@analyze')
            ->name('analyze');
    }
);
