<?php

return [
    'accessApiTokenAction' => [
        'notExistUser' => 'Пользователь не существует или не был найден.',
        'passwordNotMatch' => 'Вы указали неверный пароль.'
    ],
    'site' => [
        'accessCheckCodeResetPasswordAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.',
            'codeNotCorrect' => 'Код восстановления неверный.'
        ],
        'accessForgetAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.',
        ],
        'accessPasswordAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.',
            'passwordNotMatch' => 'Пароль неверный.'
        ],
        'accessResetAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.'
        ],
        'accessSendEmailVerificationAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.'
        ],
        'accessSendEmailVerificationCodeAction' => [
            'notExistUser' => 'Пользователь не существует или не был найден.',
            'verified' => 'Пользователь уже был верифицирован.'
        ]
    ]
];
