<?php

return [
    'requests' => [
        'admin' => [
            'collectionReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'collectionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'collectionCreateRequest' => [
                'image' => 'Изображение',
                'amount' => 'Количество курсов',
                'status' => 'Статус',
                'filters' => 'Фильтры',
            ],
            'collectionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'collectionImageUpdateRequest' => [
                'image' => 'Изображение',
            ],
            'collectionCountRequest' => [
                'filters' => 'Фильтры',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'collectionController' => [
                'create' => [
                    'log' => 'Создание коллекции.'
                ],
                'update' => [
                    'log' => 'Обновление коллекции.'
                ],
                'destroy' => [
                    'log' => 'Удаление коллекции.'
                ],
                'destroyImage' => [
                    'log' => 'Удаление изображения коллекции.'
                ]
            ],
        ],
    ]
];
