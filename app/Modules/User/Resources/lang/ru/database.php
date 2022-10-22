<?php

return [
    'seeders' => [
        'moduleTableSeeder' => [
            'name' => 'Пользователи',
            'components' => [
                'edit' => 'Редактировать',
                'info' => 'Информация'
            ],
            'widgets' => [
                'newUsers' => 'Новые пользователи'
            ]
        ],
        'userGroupTableSeeder' => [
            'admin' => 'Администратор',
            'edit' => 'Редактор',
            'user' => 'Пользователь',
        ],
        'userRoleTableSeeder' => [
            'admin' => 'Администратор',
            'edit' => 'Редактор',
            'manager' => 'Пользователь',
        ]
    ]
];
