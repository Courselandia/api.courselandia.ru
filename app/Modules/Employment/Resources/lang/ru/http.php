<?php

return [
    'requests' => [
        'admin' => [
            'employmentReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'employmentDestroyRequest' => [
                'ids' => 'ID'
            ],
            'employmentUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'employmentCreateRequest' => [
                'status' => 'Статус',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'employmentController' => [
                'create' => [
                    'log' => 'Создание трудоустройства.'
                ],
                'update' => [
                    'log' => 'Обновление трудоустройства.'
                ],
                'destroy' => [
                    'log' => 'Удаление трудоустройства.'
                ],
            ],
        ],
    ]
];
