<?php

use App\Modules\Crawl\Engines\Services\YandexService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['Welcome to API.']);
});

Route::get('/test', function () {
    $service = new YandexService();

    echo "Start: <br>";

    $taskId = $service->push('https://courselandia.ru/');

    echo $taskId . "<br>";

    return response()->view('test');
});

Route::any('/ckfinder/connector', '\CKSource\CKFinderBridge\Controller\CKFinderController@requestAction')
    ->name('ckfinder_connector');

Route::any('/ckfinder/browser/' . Config::get('ckfinder.key'), '\CKSource\CKFinderBridge\Controller\CKFinderController@browserAction')
    ->name('ckfinder_browser');
