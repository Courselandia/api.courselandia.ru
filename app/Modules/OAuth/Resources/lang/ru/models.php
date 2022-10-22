<?php

return [
    'oAuthClient' => [
        'userId' => 'ID пользователя',
        'secret' => 'Секретный ключ',
        'expiresAt' => 'Дата действия'
    ],
    'oAuthDriverDatabase' => [
        "noClient" => "Клиент не существует.",
        'noUser' => "Пользователь не существует.",
        'noValidSecretCode' => "Секретный код не существует.",
        'noRefreshCode' => "Код обновления не существует.",
        'noValidToken' => "Токен неверен."
    ],
    'oAuthRefreshToken' => [
        'oauthTokenId' => 'ID токена',
        'refreshToken' => 'Токен обновления',
        'expiresAt' => 'Дата действия'
    ],
    'oAuthToken' => [
        'oauthClientId' => 'ID клиента',
        'token' => 'Токена',
        'expiresAt' => 'Дата действия'
    ]
];
