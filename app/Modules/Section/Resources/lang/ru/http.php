<?php

return [
    'requests' => [
        'admin' => [
            'sectionReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'sectionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'sectionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'sectionCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'sectionController' => [
                'create' => [
                    'log' => 'Создание раздела.'
                ],
                'update' => [
                    'log' => 'Обновление раздела.'
                ],
                'destroy' => [
                    'log' => 'Удаление раздела.'
                ],
            ],
        ],
    ]
];
