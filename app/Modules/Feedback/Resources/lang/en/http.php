<?php

return [
    'requests' => [
        'admin' => [
            'feedbackReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'feedbackDestroyRequest' => [
                'ids' => 'ID'
            ]
        ],
        'site' => [
            'feedbackSendRequest' => [
                'name' => 'Name',
                'email' => 'E-mail',
                'phone' => 'Phone',
                'message' => 'Message'
            ]
        ]
    ],
    'controllers' => [
        'admin' => [
            'feedbackController' => [
                'destroy' => [
                    'log' => 'Destroy the feedback.'
                ],
            ],
        ],
        'site' => [
            'feedbackController' => [
                'send' => [
                    'log' => 'Create a feedback.'
                ]
            ]
        ]
    ]
];
