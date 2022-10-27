<?php

return [
    'requests' => [
        'admin' => [
            'schoolReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'schoolDestroyRequest' => [
                'ids' => 'ID'
            ],
            'schoolCreateRequest' => [
                'image' => 'Image',
            ],
            'schoolUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'schoolImageUpdateRequest' => [
                'image' => 'Image',
                'type' => 'Type',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'schoolController' => [
                'create' => [
                    'log' => 'Create a school.'
                ],
                'update' => [
                    'log' => 'Update the school.'
                ],
                'destroy' => [
                    'log' => 'Destroy the school.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the school.'
                ]
            ],
        ],
    ]
];
