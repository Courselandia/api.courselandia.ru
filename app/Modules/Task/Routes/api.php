<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/task/',
    'as' => 'api.private.admin.task'
],
    function () {
        Route::get('read/', 'Admin\TaskController@read')
            ->name('read');
    });
