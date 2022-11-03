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
                'status' => 'Status',
                'professionId' => 'Profession ID',
                'salary' => 'Salary',
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
