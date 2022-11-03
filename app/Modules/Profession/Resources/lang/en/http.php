<?php

return [
    'requests' => [
        'admin' => [
            'professionReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
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
