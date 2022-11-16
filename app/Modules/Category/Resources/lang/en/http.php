<?php

return [
    'requests' => [
        'admin' => [
            'categoryReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'categoryDestroyRequest' => [
                'ids' => 'ID'
            ],
            'categoryUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'categoryCreateRequest' => [
                'directions' => 'Directions',
                'professions' => 'Professions',
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'categoryController' => [
                'create' => [
                    'log' => 'Create a category.'
                ],
                'update' => [
                    'log' => 'Update the category.'
                ],
                'destroy' => [
                    'log' => 'Destroy the category.'
                ],
            ],
        ],
    ]
];
