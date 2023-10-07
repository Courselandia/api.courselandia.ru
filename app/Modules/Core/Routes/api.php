<?php

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/admin/core/',
    'as' => 'api.private.admin.core'
],
    function () {
        Route::post('clean/', 'Admin\CoreController@clean')
            ->name('clean');

        Route::post('typography/', 'Admin\CoreController@typography')
            ->name('typography');
    });
