<?php

return [
    'requests' => [
        'admin' => [
            'salaryReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'level' => 'Level',
                'salary' => 'Salary',
                'status' => 'Status',
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
