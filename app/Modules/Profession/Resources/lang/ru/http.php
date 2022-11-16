<?php

return [
    'requests' => [
        'admin' => [
            'professionReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
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
