<?php

return [
    'requests' => [
        'admin' => [
            'courseReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'courseDestroyRequest' => [
                'ids' => 'ID'
            ],
            'courseCreateRequest' => [
                'image' => 'Изображение',
                'publishedAt' => 'Дата публикации',
                'status' => 'Статус',
            ],
            'courseUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'courseImageUpdateRequest' => [
                'image' => 'Изображение',
            ]
        ],
        'site' => [
            'courseReadRequest' => [
                'year' => 'Год',
                'limit' => 'Лимит',
                'page' => 'Страница',
                'path' => 'Путь',
            ],
            'courseGetRequest' => [
                'id' => 'ID',
                'link' => 'Ссылка',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'courseController' => [
                'create' => [
                    'log' => 'Создание публикации.'
                ],
                'update' => [
                    'log' => 'Обновление публикации.'
                ],
                'destroy' => [
                    'log' => 'Удаление публикации.'
                ],
                'destroyImage' => [
                    'log' => 'Удаление изображения публикации.'
                ]
            ],
        ],
    ]
];
