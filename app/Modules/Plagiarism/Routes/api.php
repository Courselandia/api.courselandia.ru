<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/plagiarism/',
    'as' => 'api.private.admin.plagiarism',
],
    function () {
        Route::post('request/', 'Admin\PlagiarismController@request')
            ->name('request');

        Route::get('result/{id}', 'Admin\PlagiarismController@result')
            ->name('result');
    }
);
