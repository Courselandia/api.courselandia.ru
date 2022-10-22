<?php

return [
    'oAuthClient' => [
        'userId' => 'ID user',
        'secret' => 'Secret',
        'expiresAt' => 'Expires date'
    ],
    'oAuthDriverDatabase' => [
        "noClient" => "The client does not exist.",
        'noUser' => "The user does not exist.",
        'noValidSecretCode' => "The secret code is not valid.",
        'noRefreshCode' => "The refresh token does not exist.",
        'noValidToken' => "The token is not valid."
    ],
    'oAuthRefreshToken' => [
        'oauthToken_id' => 'ID token',
        'refreshToken' => 'Refresh token',
        'expiresAt' => 'Expires date'
    ],
    'oAuthToken' => [
        'oauthClientId' => 'ID client',
        'token' => 'Token',
        'expiresAt' => 'Expires date'
    ]
];
