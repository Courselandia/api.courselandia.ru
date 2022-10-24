<?php

return [
    'requests' => [
        'admin' => [
            'directionReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'directionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'directionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'directionController' => [
                'create' => [
                    'log' => 'Создание направления.'
                ],
                'update' => [
                    'log' => 'Обновление направления.'
                ],
                'destroy' => [
                    'log' => 'Удаление направления.'
                ],
            ],
        ],
    ]
];
