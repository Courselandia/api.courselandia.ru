<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value
    ],
    'prefix' => 'private/admin/feedback/',
    'as' => 'api.private.admin.feedback'
], function () {
    Route::get('read/', 'Admin\FeedbackController@read')
        ->name('read');

    Route::get('get/{id}', 'Admin\FeedbackController@get')
        ->name('get');

    Route::delete('destroy/', 'Admin\FeedbackController@destroy')
        ->name('destroy');
});

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/feedback/',
    'as' => 'api.private.site.feedback'
], function () {
    Route::post('send/', 'Site\FeedbackController@send')
        ->middleware('act:feedback,2,60')
        ->name('send');
});
