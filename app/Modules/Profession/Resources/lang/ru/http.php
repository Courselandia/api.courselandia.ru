<?php

return [
    'requests' => [
        'admin' => [
            'professionReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'professionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'professionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
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
