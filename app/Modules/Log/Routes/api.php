<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value
    ],
    'prefix' => 'private/admin/log/',
    'as' => 'api.private.admin.log'
], function () {
    Route::get('read/', 'Admin\LogController@read')
        ->name('read');

    Route::get('get/{id}', 'Admin\LogController@get')
        ->name('get');

    Route::delete('destroy/', 'Admin\LogController@destroy')
        ->name('destroy');
});
