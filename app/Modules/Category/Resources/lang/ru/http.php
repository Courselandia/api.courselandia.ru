<?php

return [
    'requests' => [
        'admin' => [
            'categoryReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'categoryDestroyRequest' => [
                'ids' => 'ID'
            ],
            'categoryUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'categoryCreateRequest' => [
                'directions' => 'Направления',
                'professions' => 'Профессии',
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'categoryController' => [
                'create' => [
                    'log' => 'Создание категории.'
                ],
                'update' => [
                    'log' => 'Обновление категории.'
                ],
                'destroy' => [
                    'log' => 'Удаление категории.'
                ],
            ],
        ],
    ]
];
