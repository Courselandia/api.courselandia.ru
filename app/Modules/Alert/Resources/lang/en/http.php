<?php

return [
    'requests' => [
        'site' => [
            'alertDestroy' => [
                'ids' => 'ID',
            ],
            'alertRead' => [
                'offset' => 'Offset',
                'limit' => 'Limit',
                'status' => 'Status'
            ],
            'alertStatus' => [
                'status' => 'Status'
            ]
        ]
    ],
    'controllers' => [
        'site' => [
            'alertController' => [
                'status' => [
                    'log' => 'Change status of alert.'
                ],
                'destroy' => [
                    'log' => 'Destroy the alert.'
                ],
            ]
        ]
    ]
];
