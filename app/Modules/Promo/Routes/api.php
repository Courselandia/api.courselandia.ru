<?php

Route::group([
    'middleware' => [
        'locale',
        'ajax',
    ],
    'prefix' => 'private/site/promo/',
    'as' => 'api.private.site.promo'
],
    function () {
        Route::get('read/', 'Site\PromoController@read')
            ->name('read');

        Route::get('link/{link}', 'Site\PromoController@link')
            ->name('link');

        Route::get('stat', 'Site\PromoController@link')
            ->name('stat');
    }
);
