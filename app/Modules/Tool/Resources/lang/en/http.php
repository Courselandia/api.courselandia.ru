<?php

return [
    'requests' => [
        'admin' => [
            'toolReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'toolDestroyRequest' => [
                'ids' => 'ID'
            ],
            'toolCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'toolController' => [
                'create' => [
                    'log' => 'Create a tool.'
                ],
                'update' => [
                    'log' => 'Update the tool.'
                ],
                'destroy' => [
                    'log' => 'Destroy the tool.'
                ],
            ],
        ],
    ]
];
