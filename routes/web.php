<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Welcome to API.']);
});

Route::get('/test', function () {
    $pathConfig = Config::get('crawl.google.service_account_credentials_json');
    $pathStorage = storage_path($pathConfig);

    echo $pathStorage . "<br>";

    echo Storage::get($pathConfig);

    echo "<br>";

    return response()->view('test');
});

Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser/' . Config::get('ckfinder.key'), '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');
