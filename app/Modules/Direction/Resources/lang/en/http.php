<?php

return [
    'requests' => [
        'admin' => [
            'directionReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'directionDestroyRequest' => [
                'ids' => 'ID'
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'directionController' => [
                'create' => [
                    'log' => 'Create a direction.'
                ],
                'update' => [
                    'log' => 'Update the direction.'
                ],
                'destroy' => [
                    'log' => 'Destroy the direction.'
                ],
            ],
        ],
    ]
];
