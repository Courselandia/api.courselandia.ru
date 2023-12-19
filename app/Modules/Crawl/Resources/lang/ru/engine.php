<?php

return [
    'credentials' => [
        'yandexCredential' => [
            'tokenInvalid' => 'Токен Яндекса недействителен или срок его действия истек.',
        ],
    ],
    'providers' => [
        'yandexProvider' => [
            'urlAlreadyAdded' => 'URL уже добавлен для Яндекса.',
            'quotaExceeded' => 'Превышена лимит для Яндекс.',
            'taskNotExist' => 'Задание в Yandex не найдено.',
            'failed' => 'Робот не смог обойти страницу.',
        ],
        'googleProvider' => [
            'taskNotExist' => 'Задание в Google не найдено.',
        ],
    ],
];
