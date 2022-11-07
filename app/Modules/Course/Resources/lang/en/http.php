<?php

return [
    'requests' => [
        'admin' => [
            'courseReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'courseDestroyRequest' => [
                'ids' => 'ID'
            ],
            'courseCreateRequest' => [
                'image' => 'Image',
                'schoolId' => 'School ID',
                'status' => 'Status',
                'directions' => 'Directions',
                'professions' => 'Professions',
                'categories' => 'Categories',
                'skills' => 'Skills',
                'teachers' => 'Teachers',
                'tools' => 'Tools',
                'levels' => 'Levels',
                'learns' => 'Learns',
                'employments' => 'Employments',
                'features' => 'Features',
                'features.*' => 'Features',
                'language' => 'Language',
                'rating' => 'Rating',
                'price' => 'Price',
                'price_discount' => 'Discount price',
                'price_recurrent_price' => 'Price with subscription',
                'currency' => 'Currency',
                'online' => 'Online',
                'duration' => 'Duration',
                'durationUnit' => 'Duration Unit',
                'lessons_amount' => 'Lessons amount',
                'modules_amount' => 'Modules amount',
            ],
            'courseUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'courseImageUpdateRequest' => [
                'image' => 'Image',
            ],
        ],
        'site' => [
            'courseReadRequest' => [
                'year' => 'Year',
                'limit' => 'Limit',
                'page' => 'Page',
                'path' => 'Path',
            ],
            'courseGetRequest' => [
                'id' => 'ID',
                'link' => 'Link',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'courseController' => [
                'create' => [
                    'log' => 'Create a course.'
                ],
                'update' => [
                    'log' => 'Update the course.'
                ],
                'destroy' => [
                    'log' => 'Destroy the course.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the course.'
                ]
            ],
        ],
    ]
];
