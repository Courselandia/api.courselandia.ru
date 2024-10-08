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
            ->name('update.image');

        Route::delete('destroy/image/{id}', 'Admin\CourseImageController@destroy')
            ->name('destroy.image');
    }
);

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/course/',
    'as' => 'api.private.site.course'
],
    function () {
        Route::get('get/{school}/{course}', 'Site\CourseController@get')
            ->name('get');

        Route::get('directions', 'Site\CourseController@directions')
            ->name('directions');

        Route::get('categories', 'Site\CourseController@categories')
            ->name('categories');

        Route::get('professions', 'Site\CourseController@professions')
            ->name('professions');

        Route::get('schools', 'Site\CourseController@schools')
            ->name('schools');

        Route::get('tools', 'Site\CourseController@tools')
            ->name('tools');

        Route::get('skills', 'Site\CourseController@skills')
            ->name('skills');

        Route::get('teachers', 'Site\CourseController@teachers')
            ->name('teachers');

        Route::get('stat', 'Site\CourseController@stat')
            ->name('stat');

        Route::group([
            'prefix' => 'read',
            'as' => 'read'
        ],
            function () {
                Route::get('', 'Site\CourseController@read')
                    ->name('');

                Route::get('/rated', 'Site\CourseController@readRated')
                    ->name('rated');

                Route::get('/search', 'Site\CourseController@readSearch')
                    ->name('search');

                Route::get('/favorites', 'Site\CourseController@readFavorites')
                    ->name('favorites');
            }
        );
    }
);
