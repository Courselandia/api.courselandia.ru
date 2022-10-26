<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/skill/',
    'as' => 'api.private.admin.skill'
],
    function () {
        Route::get('read/', 'Admin\SkillController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\SkillController@get')
            ->name('get');

        Route::post('create/', 'Admin\SkillController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\SkillController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\SkillController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\SkillController@destroy')
            ->name('destroy');
    });
