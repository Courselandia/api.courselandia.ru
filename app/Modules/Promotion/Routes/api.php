<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/promotion/',
    'as' => 'api.private.admin.promotion.'
],
    function () {
        Route::get('read/', 'Admin\PromotionController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\PromotionController@get')
            ->name('get');

        Route::post('create/', 'Admin\PromotionController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\PromotionController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\PromotionController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\PromotionController@destroy')
            ->name('destroy');
    });
