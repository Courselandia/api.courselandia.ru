<?php

return [
    'requests' => [
        'admin' => [
            'skillReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'skillDestroyRequest' => [
                'ids' => 'ID'
            ],
            'skillUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'skillCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'skillController' => [
                'create' => [
                    'log' => 'Создание навыка.'
                ],
                'update' => [
                    'log' => 'Обновление навыка.'
                ],
                'destroy' => [
                    'log' => 'Удаление навыка.'
                ],
            ],
        ],
    ]
];
