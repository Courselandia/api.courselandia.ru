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
            'widgetUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'widgetCreateRequest' => [
                'status' => 'Статус',
                'name' => 'Название значения',
                'value' => 'Значения',
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
