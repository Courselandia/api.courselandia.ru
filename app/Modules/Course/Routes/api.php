<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/course/',
    'as' => 'api.private.admin.course'
],
    function () {
        Route::get('read/', 'Admin\CourseController@read')
            ->name('read');

        Route::get('get/{id}', 'Admin\CourseController@get')
            ->name('get');

        Route::post('create/', 'Admin\CourseController@create')
            ->name('create');

        Route::put('update/{id}', 'Admin\CourseController@update')
            ->name('update');

        Route::delete('destroy/', 'Admin\CourseController@destroy')
            ->name('destroy');

        Route::put('update/image/{id}', 'Admin\CourseImageController@update')
            ->name('destroy');

        Route::delete('destroy/image/{id}', 'Admin\CourseImageController@destroy')
            ->name('destroy');
    }
);
