<?php

return [
    'requests' => [
        'admin' => [
            'professionReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'professionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'professionCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'professionController' => [
                'create' => [
                    'log' => 'Create a profession.'
                ],
                'update' => [
                    'log' => 'Update the profession.'
                ],
                'destroy' => [
                    'log' => 'Destroy the profession.'
                ],
            ],
        ],
    ]
];
