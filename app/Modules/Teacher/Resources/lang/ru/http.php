<?php

return [
    'requests' => [
        'admin' => [
            'teacherReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'teacherDestroyRequest' => [
                'ids' => 'ID'
            ],
            'teacherCreateRequest' => [
                'image' => 'Изображение',
                'directions' => 'Направления',
                'schools' => 'Школы',
                'rating' => 'Рейтинг',
                'status' => 'Статус',
            ],
            'teacherUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'teacherImageUpdateRequest' => [
                'image' => 'Изображение',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'teacherController' => [
                'create' => [
                    'log' => 'Создание учителя.'
                ],
                'update' => [
                    'log' => 'Обновление учителя.'
                ],
                'destroy' => [
                    'log' => 'Удаление учителя.'
                ],
                'destroyImage' => [
                    'log' => 'Удаление изображения учителя.'
                ]
            ],
        ],
    ]
];
