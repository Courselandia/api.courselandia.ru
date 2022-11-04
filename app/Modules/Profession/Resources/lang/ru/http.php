<?php

return [
    'requests' => [
        'admin' => [
            'professionReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'professionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'professionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'professionCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'professionController' => [
                'create' => [
                    'log' => 'Создание профессии.'
                ],
                'update' => [
                    'log' => 'Обновление профессии.'
                ],
                'destroy' => [
                    'log' => 'Удаление профессии.'
                ],
            ],
        ],
    ]
];
