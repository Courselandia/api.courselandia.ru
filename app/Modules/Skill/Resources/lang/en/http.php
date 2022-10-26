<?php

return [
    'requests' => [
        'admin' => [
            'skillReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'skillDestroyRequest' => [
                'ids' => 'ID'
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'skillController' => [
                'create' => [
                    'log' => 'Create a skill.'
                ],
                'update' => [
                    'log' => 'Update the skill.'
                ],
                'destroy' => [
                    'log' => 'Destroy the skill.'
                ],
            ],
        ],
    ]
];
