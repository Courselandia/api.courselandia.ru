<?php

return [
    'requests' => [
        'admin' => [
            'user' => [
                'userReadRequest' => [
                    'sorts' => 'Сортировки',
                    'offset' => 'Отступ',
                    'limit' => 'Лимит',
                    'filters' => 'Фильтры',
                    'status' => 'Статус',
                ],
                'userDestroyRequest' => [
                    'ids' => 'ID'
                ],
                'userCreateRequest' => [
                    'image' => 'Изображение',
                    'invitation' => 'Приглашение',
                    'role' => 'Роль',
                    'verified' => 'Верифицирован',
                    'two_factor' => 'Двухфакторная аутентификация',
                    'status' => 'Статус',
                ],
                'userUpdateStatusRequest' => [
                    'status' => 'Статус'
                ],
                'userImageUpdateRequest' => [
                    'image' => 'Изображение'
                ],
                'userProfileUpdateRequest' => [
                    'image' => 'Изображение'
                ]
            ],
            'userAnalytics' => [
                'userAnalyticsNewUsersRequest' => [
                    'group' => 'Группировка',
                    'date' => 'Период',
                    'dateFrom' => 'Дата от',
                    'dateTo' => 'Дата до'
                ],
            ],
            'config' => [
                'userConfigUpdateRequest' => [
                    'configs' => 'Конфигурации'
                ]
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'userController' => [
                'get' => [
                    'log' => 'Получение пользователя.'
                ],
                'read' => [
                    'log' => 'Чтение пользователей.'
                ],
                'create' => [
                    'log' => 'Создание пользователя.',
                ],
                'update' => [
                    'log' => 'Обновление пользователя.',
                ],
                'password' => [
                    'log' => 'Обновление пароля пользователя.',
                ],
                'destroy' => [
                    'log' => 'Удаление пользователя.',
                ]
            ],
            'userConfigController' => [
                'get' => [
                    'log' => 'Получение конфигурации пользователя.'
                ],
                'update' => [
                    'log' => 'Обновление конфигурации пользователя.',
                ],
            ],
            'userImageController' => [
                'get' => [
                    'log' => 'Получение изображения пользователя.'
                ],
                'update' => [
                    'log' => 'Обновление изображения пользователя.',
                ],
                'destroy' => [
                    'log' => 'Удаление изображения пользователя.',
                ]
            ],
            'userRoleController' => [
                'create' => [
                    'log' => 'Создание роли.'
                ],
                'update' => [
                    'log' => 'Обновление роли.'
                ],
                'destroy' => [
                    'log' => 'Удаление роли.'
                ],
            ],
            'userGroupController' => [
                'create' => [
                    'log' => 'Создание группы.'
                ],
                'update' => [
                    'log' => 'Обновление группы.'
                ],
                'destroy' => [
                    'log' => 'Удаление группы.'
                ],
            ],
        ]
    ]
];
