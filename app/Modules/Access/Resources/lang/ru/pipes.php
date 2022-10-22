<?php

return [
    'site' => [
        'update' => [
            'userPipe' => [
                'notExistUser' => "Пользователь не существует или не найден."
            ]
        ],
        'verified' => [
            'checkPipe' => [
                'notExistUser' => "Пользователь не существует или не найден.",
                'notExistCode' => "Верификационный код не существует.",
                'notCorrectCode' => "Верификационный код неверный",
                'userIsVerified' => "Пользователь уже был верифицирован."
            ]
        ],
        'signUp' => [
            'notExistGroup' => "Группа :name не существует или не была найдена.",
        ]
    ]
];
