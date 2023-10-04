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

        Route::get('read/courses/{id}', 'Admin\TeacherController@courses')
            ->name('read.courses');

        Route::get('detach/courses/{id}', 'Admin\TeacherController@detachCourses')
            ->name('detach.courses');

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

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/teacher/',
    'as' => 'api.private.site.teacher'
],
    function () {
        Route::get('get/{id}', 'Site\TeacherController@get')
            ->name('get');

        Route::get('link/{link}', 'Site\TeacherController@link')
            ->name('link');
    }
);
