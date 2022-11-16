<?php

return [
    'requests' => [
        'admin' => [
            'schoolReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'schoolDestroyRequest' => [
                'ids' => 'ID'
            ],
            'schoolCreateRequest' => [
                'imageSite' => 'Image Site',
                'imageLogo' => 'Image Logo',
                'rating' => 'Rating',
                'status' => 'Status',
            ],
            'schoolUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'schoolImageUpdateRequest' => [
                'image' => 'Image',
                'type' => 'Type',
            ],
        ],
        'site' => [
            'schoolReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
            ],
        ]
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
