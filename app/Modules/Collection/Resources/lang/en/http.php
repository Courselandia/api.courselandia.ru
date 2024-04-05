<?php

return [
    'requests' => [
        'admin' => [
            'collectionReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'collectionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'collectionCreateRequest' => [
                'image' => 'Image',
                'amount' => 'Amount',
                'status' => 'Status',
                'filters' => 'Filters',
            ],
            'collectionUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'collectionImageUpdateRequest' => [
                'image' => 'Image',
            ],
            'collectionCountRequest' => [
                'filters' => 'Filters',
            ],
        ],
        'site' => [
            'collectionReadRequest' => [
                'limit' => 'Limit',
                'offset' => 'Offset',
                'direction' => 'Direction',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'collectionController' => [
                'create' => [
                    'log' => 'Create a collection.',
                ],
                'update' => [
                    'log' => 'Update the collection.',
                ],
                'destroy' => [
                    'log' => 'Destroy the collection.',
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the collection.',
                ],
            ],
        ],
    ],
];
