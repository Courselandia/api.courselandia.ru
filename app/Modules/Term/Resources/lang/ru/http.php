<?php

return [
    'requests' => [
        'admin' => [
            'termReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'termDestroyRequest' => [
                'ids' => 'ID',
            ],
            'termUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'termCreateRequest' => [
                'status' => 'Статус',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'termController' => [
                'create' => [
                    'log' => 'Создание термина.',
                ],
                'update' => [
                    'log' => 'Обновление термина.',
                ],
                'destroy' => [
                    'log' => 'Удаление термина.',
                ],
            ],
        ],
    ],
];
