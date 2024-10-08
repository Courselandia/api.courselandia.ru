<?php

return [
    'name' => 'Document',

    /*
    |--------------------------------------------------------------------------
    | Мягкое удаление
    |--------------------------------------------------------------------------
    |
    | Позволяет не удалять физически документа с жесткого диска
    |
    */
    'soft_deletes' => env('DOCUMENT_SOFT', true),

    /*
    |--------------------------------------------------------------------------
    | Место хранения записей об документах
    |--------------------------------------------------------------------------
    |
    | Здесь можно определить место хранения для записей об документах.
    | Доступны значения: "database" - база данных, "mongodb" - MongoDb
    |
    */
    'record' => env('DOCUMENT', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Драйвер хранения документов
    |--------------------------------------------------------------------------
    |
    | Определяем систему хранения документов.
    | base - в базе данных, local - локально в папке, ftp - через FTP протокол в папке
    | http - через HTTP протокол в папке
    |
    */
    'store_driver' => env('DOCUMENT_DRIVER', 'local'),


    /*
    |--------------------------------------------------------------------------
    | Настройка хранилища для документов
    |--------------------------------------------------------------------------
    |
    | В этом месте можно определить доступы к хранилищу документов
    |
    */
    'store' => [
        'base' => [
            'table' => 'documents',
            'property' => 'byte'
        ],
        'local' => [
            'path' => 'storage/documents/',
            'pathSource' => storage_path('app/public/documents/'),
        ],
        'ftp' => [
            'server' => 'courselandia.ru ',
            'login' => 'courselandia',
            'password' => 'O4z1S0f6',
            'path' => 'www/documents/'
        ],
        'http' => [
            'read' => 'http://loc.courselandia.ru/storage/documents/',
            'create' => 'http://loc.courselandia.ru/img/create/',
            'update' => 'http://loc.courselandia.ru/img/update/',
            'destroy' => 'http://loc.courselandia.ru/img/destroy/',
        ]
    ]
];
