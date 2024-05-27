<?php

return [
    'requests' => [
        'admin' => [
            'promocodeReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'promocodeDestroyRequest' => [
                'ids' => 'ID',
            ],
            'promocodeCreateRequest' => [
                'dateStart' => 'Start date of promocode',
                'dateEnd' => 'End date of promocode',
                'status' => 'Status',
                'schoolId' => 'School ID',
                'minPrice' => 'Min price',
                'discount' => 'Discount',
                'discountType' => 'Discount type',
                'type' => 'Type',
            ],
            'promocodeUpdateStatusRequest' => [
                'status' => 'Status',
            ],
        ],
        'site' => [
            'promocodeReadRequest' => [
                'year' => 'Year',
                'limit' => 'Limit',
                'offset' => 'Offset',
                'path' => 'Path',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'promocodeController' => [
                'create' => [
                    'log' => 'Create a promocode.',
                ],
                'update' => [
                    'log' => 'Update the promocode.',
                ],
                'destroy' => [
                    'log' => 'Destroy the promocode.',
                ],
            ],
        ],
    ],
];
