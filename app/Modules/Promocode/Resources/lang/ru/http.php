<?php

return [
    'requests' => [
        'admin' => [
            'promocodeReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'promocodeDestroyRequest' => [
                'ids' => 'ID'
            ],
            'promocodeCreateRequest' => [
                'dateStart' => 'Дата начала',
                'dateEnd' => 'Дата окончания',
                'status' => 'Статус',
                'schoolId' => 'ID школы',
                'minPrice' => 'Минимальная цена',
                'discount' => 'Скидка',
                'discountType' => 'Тип скидки',
                'type' => 'Тип промокода',
            ],
            'promocodeUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
        ],
        'site' => [
            'promocodeReadRequest' => [
                'year' => 'Год',
                'limit' => 'Лимит',
                'offset' => 'Отступ',
                'path' => 'Путь',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'promocodeController' => [
                'create' => [
                    'log' => 'Создание публикации.',
                ],
                'update' => [
                    'log' => 'Обновление публикации.',
                ],
                'destroy' => [
                    'log' => 'Удаление публикации.',
                ],
            ],
        ],
    ],
];
