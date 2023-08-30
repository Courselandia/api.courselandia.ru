<?php

return [
    'requests' => [
        'admin' => [
            'reviewReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
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
                'createdAt' => 'Created At',
            ],
        ],
        'site' => [
            'reviewReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'schoolId' => 'ID school',
                'link' => 'Link',
                'rating' => 'Rating',
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
