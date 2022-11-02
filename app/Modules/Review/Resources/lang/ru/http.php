<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'reviewDestroyRequest' => [
                'ids' => 'ID'
            ],
            'reviewUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'reviewCreateRequest' => [
                'level' => 'Уровень',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'reviewController' => [
                'create' => [
                    'log' => 'Создание отзывов.'
                ],
                'update' => [
                    'log' => 'Обновление отзывов.'
                ],
                'destroy' => [
                    'log' => 'Удаление отзывов.'
                ],
            ],
        ],
    ]
];
