<?php

return [
    'requests' => [
        'admin' => [
            'schoolReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'schoolDestroyRequest' => [
                'ids' => 'ID'
            ],
            'schoolCreateRequest' => [
                'imageLogo' => 'Изображение логотипа',
                'imageSite' => 'Изображение сайта',
                'rating' => 'Рейтинг',
                'status' => 'Статус',
            ],
            'schoolUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'schoolImageUpdateRequest' => [
                'image' => 'Изображение',
                'type' => 'Тип изображения',
            ]
        ],
        'site' => [
            'schoolReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
            ]
        ]
    ],
    'controllers' => [
        'admin' => [
            'schoolController' => [
                'create' => [
                    'log' => 'Создание школы.'
                ],
                'update' => [
                    'log' => 'Обновление школы.'
                ],
                'destroy' => [
                    'log' => 'Удаление школы.'
                ],
                'destroyImage' => [
                    'log' => 'Удаление изображения школы.'
                ]
            ],
        ],
    ]
];
