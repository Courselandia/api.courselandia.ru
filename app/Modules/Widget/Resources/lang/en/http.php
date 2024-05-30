<?php

return [
    'requests' => [
        'admin' => [
            'widgetReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'widgetDestroyRequest' => [
                'ids' => 'ID',
            ],
            'widgetCreateRequest' => [
                'status' => 'Status',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'widgetController' => [
                'create' => [
                    'log' => 'Create a widget.',
                ],
                'update' => [
                    'log' => 'Update the widget.',
                ],
                'destroy' => [
                    'log' => 'Destroy the widget.',
                ],
            ],
        ],
    ],
];
