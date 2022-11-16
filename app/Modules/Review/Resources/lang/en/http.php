<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'rating' => 'Rating',
                'status' => 'Status',
            ],
            'reviewDestroyRequest' => [
                'ids' => 'ID'
            ],
            'reviewCreateRequest' => [
                'status' => 'Status',
                'rating' => 'Rating',
                'schoolId' => 'School ID',
            ],
        ],
        'site' => [
            'reviewReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'schoolId' => 'ID school',
            ],
        ]
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
