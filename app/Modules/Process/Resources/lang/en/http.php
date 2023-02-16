<?php

return [
    'requests' => [
        'admin' => [
            'processReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'processDestroyRequest' => [
                'ids' => 'ID'
            ],
            'processCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'processController' => [
                'create' => [
                    'log' => 'Create a process.'
                ],
                'update' => [
                    'log' => 'Update the process.'
                ],
                'destroy' => [
                    'log' => 'Destroy the process.'
                ],
            ],
        ],
    ]
];
