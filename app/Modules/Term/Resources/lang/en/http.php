<?php

return [
    'requests' => [
        'admin' => [
            'termReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'termDestroyRequest' => [
                'ids' => 'ID'
            ],
            'termCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'termController' => [
                'create' => [
                    'log' => 'Create a term.'
                ],
                'update' => [
                    'log' => 'Update the term.'
                ],
                'destroy' => [
                    'log' => 'Destroy the term.'
                ],
            ],
        ],
    ]
];
