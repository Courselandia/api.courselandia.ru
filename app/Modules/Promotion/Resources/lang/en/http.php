<?php

return [
    'requests' => [
        'admin' => [
            'promotionReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'promotionDestroyRequest' => [
                'ids' => 'ID',
            ],
            'promotionCreateRequest' => [
                'dateStart' => 'Start date of promotion',
                'dateEnd' => 'End date of promotion',
                'status' => 'Status',
                'school_id' => 'ID school',
            ],
            'promotionUpdateStatusRequest' => [
                'status' => 'Status',
            ],
        ],
        'site' => [
            'promotionReadRequest' => [
                'year' => 'Year',
                'limit' => 'Limit',
                'offset' => 'Offset',
                'path' => 'Path',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'promotionController' => [
                'create' => [
                    'log' => 'Create a promotion.',
                ],
                'update' => [
                    'log' => 'Update the promotion.',
                ],
                'destroy' => [
                    'log' => 'Destroy the promotion.',
                ],
            ],
        ],
    ],
];
