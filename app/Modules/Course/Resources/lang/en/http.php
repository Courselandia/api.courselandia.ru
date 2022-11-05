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
                'publishedAt' => 'Date of course',
                'status' => 'Status',
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
