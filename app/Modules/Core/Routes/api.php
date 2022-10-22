<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:'.Role::ADMIN->value.','.Role::MANAGER->value
    ],
    'prefix' => 'private/admin/core/',
    'as' => 'api.private.admin.core'
],
    function () {
        Route::post('clean/', 'Admin\CoreController@clean')
            ->name('clean');

        Route::get('typography/', 'Admin\CoreController@typography')
            ->name('typography');
    });
