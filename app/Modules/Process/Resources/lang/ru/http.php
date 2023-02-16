<?php

return [
    'requests' => [
        'admin' => [
            'processReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'processDestroyRequest' => [
                'ids' => 'ID'
            ],
            'processUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'processCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'processController' => [
                'create' => [
                    'log' => 'Создание объяснения как проходит обучение.'
                ],
                'update' => [
                    'log' => 'Обновление объяснения как проходит обучение.'
                ],
                'destroy' => [
                    'log' => 'Удаление объяснения как проходит обучение.'
                ],
            ],
        ],
    ]
];
