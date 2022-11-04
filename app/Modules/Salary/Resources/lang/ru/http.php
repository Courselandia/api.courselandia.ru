<?php

return [
    'requests' => [
        'admin' => [
            'salaryReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'level' => 'Уровень',
                'salary' => 'Зарплата',
                'status' => 'Статус',
            ],
            'salaryDestroyRequest' => [
                'ids' => 'ID'
            ],
            'salaryUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'salaryCreateRequest' => [
                'level' => 'Уровень',
                'status' => 'Статус',
                'professionId' => 'ID профессии',
                'salary' => 'Зарплата',
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
