<?php

return [
    'requests' => [
        'admin' => [
            'teacherReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'teacherDestroyRequest' => [
                'ids' => 'ID'
            ],
            'teacherCreateRequest' => [
                'image' => 'Image',
                'directions' => 'Directions',
                'schools' => 'Schools',
                'rating' => 'Rating',
                'status' => 'Status',
            ],
            'teacherUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'teacherImageUpdateRequest' => [
                'image' => 'Image',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'teacherController' => [
                'create' => [
                    'log' => 'Create a teacher.'
                ],
                'update' => [
                    'log' => 'Update the teacher.'
                ],
                'destroy' => [
                    'log' => 'Destroy the teacher.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the teacher.'
                ]
            ],
        ],
    ]
];
