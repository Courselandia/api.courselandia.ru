<?php

return [
    'name' => 'Crawl',
    'yandex' => [
        'token' => env('YANDEX_TOKEN'),
        'webmaster_host' => env('YANDEX_WEBMASTER_HOST'),
    ],
    'google' => [
        'application_name' => env('GOOGLE_APPLICATION_NAME'),
        'service_account_credentials_json' => env('GOOGLE_SERVICE_ACCOUNT_CREDENTIALS_JSON'),
        'scopes' => [
            'https://www.googleapis.com/auth/indexing',
        ],
    ],
];
