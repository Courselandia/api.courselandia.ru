<?php

return [
    'requests' => [
        'admin' => [
            'directionReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'directionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'directionCreateRequest' => [
                'weight' => 'Weight',
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'directionController' => [
                'create' => [
                    'log' => 'Create a direction.'
                ],
                'update' => [
                    'log' => 'Update the direction.'
                ],
                'destroy' => [
                    'log' => 'Destroy the direction.'
                ],
            ],
        ],
    ]
];
