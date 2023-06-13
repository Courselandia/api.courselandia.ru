<?php

return [
    'requests' => [
        'admin' => [
            'analyzerReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'analyzerController' => [
                'update' => [
                    'log' => 'Update the data.'
                ],
                'analyze' => [
                    'log' => 'Analyze.'
                ],
            ],
        ],
    ]
];
