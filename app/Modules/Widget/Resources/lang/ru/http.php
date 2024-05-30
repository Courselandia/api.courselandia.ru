<?php

return [
    'requests' => [
        'admin' => [
            'widgetReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'widgetDestroyRequest' => [
                'ids' => 'ID',
            ],
            'widgetUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'widgetCreateRequest' => [
                'status' => 'Статус',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'widgetController' => [
                'create' => [
                    'log' => 'Создание виджета.',
                ],
                'update' => [
                    'log' => 'Обновление виджета.',
                ],
                'destroy' => [
                    'log' => 'Удаление виджета.',
                ],
            ],
        ],
    ],
];
