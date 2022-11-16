<?php

return [
    'requests' => [
        'admin' => [
            'skillReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'skillDestroyRequest' => [
                'ids' => 'ID'
            ],
            'skillCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'skillController' => [
                'create' => [
                    'log' => 'Create a skill.'
                ],
                'update' => [
                    'log' => 'Update the skill.'
                ],
                'destroy' => [
                    'log' => 'Destroy the skill.'
                ],
            ],
        ],
    ]
];
