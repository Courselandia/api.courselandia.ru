<?php

return [
    'credentials' => [
        'yandexCredential' => [
            'tokenInvalid' => 'Yandex token invalid or expired.',
        ],
    ],
    'providers' => [
        'yandexProvider' => [
            'urlAlreadyAdded' => 'Url already added for Yandex.',
            'quotaExceeded' => 'Quota exceeded for Yandex.',
            'taskNotExist' => 'Task does not exist in Yandex.',
            'failed' => 'The crawl error',
        ],
        'googleProvider' => [
            'taskNotExist' => 'Task does not exist in Google.',
        ],
    ],
];
