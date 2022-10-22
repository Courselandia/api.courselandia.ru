<?php

return [
    'requests' => [
        'admin' => [
            'publicationReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'publicationDestroyRequest' => [
                'ids' => 'ID'
            ],
            'publicationCreateRequest' => [
                'image' => 'Изображение',
                'publishedAt' => 'Дата публикации',
            ],
            'publicationUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'publicationImageUpdateRequest' => [
                'image' => 'Изображение',
            ]
        ],
        'site' => [
            'publicationReadRequest' => [
                'year' => 'Год',
                'limit' => 'Лимит',
                'page' => 'Страница',
                'path' => 'Путь',
            ],
            'publicationGetRequest' => [
                'id' => 'ID',
                'link' => 'Ссылка',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'publicationController' => [
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
