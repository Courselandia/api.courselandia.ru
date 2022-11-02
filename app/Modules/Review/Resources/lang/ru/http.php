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
            'reviewCreateRequest' => [
                'status' => 'Статус',
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
