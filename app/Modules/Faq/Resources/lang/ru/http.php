<?php

return [
    'requests' => [
        'admin' => [
            'faqReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'faqDestroyRequest' => [
                'ids' => 'ID'
            ],
            'faqCreateRequest' => [
                'status' => 'Статус',
                'rating' => 'Рейтинг',
                'schoolId' => 'ID школы',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'faqController' => [
                'create' => [
                    'log' => 'Создание FAQ.'
                ],
                'update' => [
                    'log' => 'Обновление FAQ.'
                ],
                'destroy' => [
                    'log' => 'Удаление FAQ.'
                ],
            ],
        ],
    ]
];
