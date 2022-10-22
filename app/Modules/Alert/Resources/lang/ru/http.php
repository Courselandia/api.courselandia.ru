<?php

return [
    'requests' => [
        'alertDestroy' => [
            'ids' => 'ID',
        ],
        'alertRead' => [
            'offset' => 'Отступ',
            'limit' => 'Лимит',
            'status' => 'Статус'
        ],
        'alertStatus' => [
            'status' => 'Статус'
        ]
    ],
    'controllers' => [
        'alertController' => [
            'status' => [
                'log' => 'Изменение статуса предупреждения.'
            ],
            'destroy' => [
                'log' => 'Удаление предупреждения.'
            ],
        ]
    ]
];
