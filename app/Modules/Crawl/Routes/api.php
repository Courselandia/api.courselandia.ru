<?php

use App\Modules\User\Enums\Role;

Route::group([
    'middleware' => [
        'locale',
        'ajax',
        'auth.api',
        'auth.role:' . Role::ADMIN->value . ',' . Role::MANAGER->value,
    ],
    'prefix' => 'private/admin/crawl/',
    'as' => 'api.private.admin.crawl',
],
    function () {
        Route::get('read/', 'Admin\CrawlController@read')
            ->name('read');
    }
);
