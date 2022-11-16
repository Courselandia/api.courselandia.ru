<?php

return [
    'requests' => [
        'admin' => [
            'publicationReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'publicationDestroyRequest' => [
                'ids' => 'ID'
            ],
            'publicationCreateRequest' => [
                'image' => 'Изображение',
                'publishedAt' => 'Дата публикации',
                'status' => 'Статус',
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
                'offset' => 'Отступ',
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
