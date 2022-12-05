<?php

use App\Modules\Course\Imports\Import;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Welcome to API.']);
});

Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser', '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');

Route::get('/import', function () {
    $import = new Import();
    $import->run();

    return '';
});
