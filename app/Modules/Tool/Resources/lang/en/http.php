<?php

return [
    'requests' => [
        'admin' => [
            'toolReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'toolDestroyRequest' => [
                'ids' => 'ID'
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'toolController' => [
                'create' => [
                    'log' => 'Create a tool.'
                ],
                'update' => [
                    'log' => 'Update the tool.'
                ],
                'destroy' => [
                    'log' => 'Destroy the tool.'
                ],
            ],
        ],
    ]
];
