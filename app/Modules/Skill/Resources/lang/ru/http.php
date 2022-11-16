<?php

return [
    'requests' => [
        'admin' => [
            'skillReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
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
