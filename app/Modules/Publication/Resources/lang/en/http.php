<?php

return [
    'requests' => [
        'admin' => [
            'publicationReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'publicationDestroyRequest' => [
                'ids' => 'ID'
            ],
            'publicationCreateRequest' => [
                'image' => 'Image',
                'publishedAt' => 'Date of publication',
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
                'page' => 'Page',
                'path' => 'Path',
            ],
            'publicationGetRequest' => [
                'id' => 'ID',
                'link' => 'Link',
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
