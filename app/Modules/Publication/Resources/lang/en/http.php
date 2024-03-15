<?php

return [
    'requests' => [
        'admin' => [
            'publicationReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'publicationDestroyRequest' => [
                'ids' => 'ID'
            ],
            'publicationCreateRequest' => [
                'image' => 'Image',
                'publishedAt' => 'Date of publication',
                'status' => 'Status',
            ],
            'publicationUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'publicationImageUpdateRequest' => [
                'image' => 'Image',
            ],
        ],
        'site' => [
            'publicationReadRequest' => [
                'year' => 'Year',
                'limit' => 'Limit',
                'offset' => 'Offset',
                'path' => 'Path',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'publicationController' => [
                'create' => [
                    'log' => 'Create a publication.'
                ],
                'update' => [
                    'log' => 'Update the publication.'
                ],
                'destroy' => [
                    'log' => 'Destroy the publication.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the publication.'
                ]
            ],
        ],
    ]
];
