<?php

return [
    'requests' => [
        'admin' => [
            'feedbackReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтры'
            ],
            'feedbackDestroyRequest' => [
                'ids' => 'ID'
            ]
        ],
        'site' => [
            'feedbackSendRequest' => [
                'name' => 'Название',
                'email' => 'Почта',
                'phone' => 'Телефон',
                'message' => 'Сообщение'
            ]
        ]
    ],
    'controllers' => [
        'admin' => [
            'feedbackController' => [
                'destroy' => [
                    'log' => 'Удаление обратной связи.'
                ],
            ],
        ],
        'site' => [
            'feedbackController' => [
                'send' => [
                    'log' => 'Создание обратной связи.'
                ]
            ]
        ]
    ]
];
