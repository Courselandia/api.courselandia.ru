<?php

return [
    'requests' => [
        'admin' => [
            'analyzerReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'analyzerController' => [
                'update' => [
                    'log' => 'Обновление данных.'
                ],
                'analyze' => [
                    'log' => 'Анализирование.'
                ],
            ],
        ],
    ]
];
