<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'reviewDestroyRequest' => [
                'ids' => 'ID'
            ],
            'reviewCreateRequest' => [
                'status' => 'Status',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'reviewController' => [
                'create' => [
                    'log' => 'Create a review.'
                ],
                'update' => [
                    'log' => 'Update the review.'
                ],
                'destroy' => [
                    'log' => 'Destroy the review.'
                ],
            ],
        ],
    ]
];
