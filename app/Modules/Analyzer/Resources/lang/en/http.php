<?php

return [
    'requests' => [
        'admin' => [
            'articleReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'articleUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'articleRewriteRequest' => [
                'request' => 'Request',
            ],
            'articleUpdateRequest' => [
                'apply' => 'Apply',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'articleController' => [
                'update' => [
                    'log' => 'Update the article.'
                ],
                'rewrite' => [
                    'log' => 'Rewrite the article.'
                ],
                'apply' => [
                    'log' => 'Apply the article.'
                ],
            ],
        ],
    ]
];
