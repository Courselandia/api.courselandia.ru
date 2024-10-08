<?php

return [
    'requests' => [
        'admin' => [
            'toolReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'toolDestroyRequest' => [
                'ids' => 'ID'
            ],
            'toolUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'toolCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'toolController' => [
                'create' => [
                    'log' => 'Создание инструмента.'
                ],
                'update' => [
                    'log' => 'Обновление инструмента.'
                ],
                'destroy' => [
                    'log' => 'Удаление инструмента.'
                ],
            ],
        ],
    ]
];
