<?php

return [
    'requests' => [
        'admin' => [
            'employmentReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'employmentDestroyRequest' => [
                'ids' => 'ID'
            ],
            'employmentCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'employmentController' => [
                'create' => [
                    'log' => 'Create a employment.'
                ],
                'update' => [
                    'log' => 'Update the employment.'
                ],
                'destroy' => [
                    'log' => 'Destroy the employment.'
                ],
            ],
        ],
    ]
];
