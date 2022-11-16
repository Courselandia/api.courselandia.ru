<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'rating' => 'Рейтинг',
                'status' => 'Статус',
            ],
            'reviewDestroyRequest' => [
                'ids' => 'ID'
            ],
            'reviewCreateRequest' => [
                'status' => 'Статус',
                'rating' => 'Рейтинг',
                'schoolId' => 'ID школы',
            ],
        ],
        'site' => [
            'reviewReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'schoolId' => 'ID школы',
            ],
        ]
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
