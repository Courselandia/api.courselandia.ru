<?php

return [
    'requests' => [
        'admin' => [
            'salaryReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
            ],
            'salaryDestroyRequest' => [
                'ids' => 'ID'
            ],
            'salaryUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'salaryCreateRequest' => [
                'level' => 'Уровень',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'salaryController' => [
                'create' => [
                    'log' => 'Создание зарплаты.'
                ],
                'update' => [
                    'log' => 'Обновление зарплаты.'
                ],
                'destroy' => [
                    'log' => 'Удаление зарплаты.'
                ],
            ],
        ],
    ]
];
