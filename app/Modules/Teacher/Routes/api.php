<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/teacher/',
    'as' => 'api.private.admin.teacher'
],
    function () {
        Route::get('read/', 'Admin\TeacherController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\TeacherController@get')
            ->name('get');

        Route::post('create/', 'Admin\TeacherController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\TeacherController@update')
            ->name('update');

        Route::put('update/status/{id}', 'Admin\TeacherController@updateStatus')
            ->name('update.status');

        Route::delete('destroy/', 'Admin\TeacherController@destroy')
            ->name('destroy');

        Route::put('update/image/{id}', 'Admin\TeacherImageController@update')
            ->name('update.image');

        Route::delete('destroy/image/{id}', 'Admin\TeacherImageController@destroy')
            ->name('destroy.image');
    });
