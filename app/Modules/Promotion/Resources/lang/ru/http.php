<?php

return [
    'requests' => [
        'admin' => [
            'promotionReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'promotionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'promotionCreateRequest' => [
                'dateStart' => 'Дата начала',
                'dateEnd' => 'Дата окончания',
                'status' => 'Статус',
                'school_id' => 'ID школы',
            ],
            'promotionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
        ],
        'site' => [
            'promotionReadRequest' => [
                'year' => 'Год',
                'limit' => 'Лимит',
                'offset' => 'Отступ',
                'path' => 'Путь',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'promotionController' => [
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
