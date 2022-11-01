<?php

return [
    'requests' => [
        'admin' => [
            'salaryReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
            ],
            'salaryDestroyRequest' => [
                'ids' => 'ID'
            ],
            'salaryCreateRequest' => [
                'level' => 'Level',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'salaryController' => [
                'create' => [
                    'log' => 'Create a salary.'
                ],
                'update' => [
                    'log' => 'Update the salary.'
                ],
                'destroy' => [
                    'log' => 'Destroy the salary.'
                ],
            ],
        ],
    ]
];
