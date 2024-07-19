<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Welcome to API.']);
});

Route::get('/cache-test', function () {
    \Cache::remember('cache-test', 86400, function () {
        echo "GETTING...\n";
        return 'YES!';
    });

    return response()->json(['Test Cache.']);
});

Route::get('/test', function () {
    return response()->view('test');
});

Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser/' . Config::get('ckfinder.key'), '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');
