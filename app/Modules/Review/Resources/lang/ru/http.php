<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
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
                'createdAt' => 'Дата создания',
                'link' => 'Ссылка',
            ],
        ],
        'site' => [
            'reviewReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'schoolId' => 'ID школы',
                'rating' => 'Рейтинг',
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
