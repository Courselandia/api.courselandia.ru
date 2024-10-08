<?php

return [
    'requests' => [
        'admin' => [
            'articleReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'articleUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'articleRewriteRequest' => [
                'request' => 'Запрос',
            ],
            'articleUpdateRequest' => [
                'apply' => 'Принять',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'articleController' => [
                'update' => [
                    'log' => 'Обновление текста.'
                ],
                'rewrite' => [
                    'log' => 'Переписание текста.'
                ],
                'apply' => [
                    'log' => 'Текст был принят.'
                ],
            ],
        ],
    ]
];
