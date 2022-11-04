<?php

return [
    'requests' => [
        'admin' => [
            'faqReadRequest' => [
                'sorts' => 'Sorts',
                'start' => 'Start',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'faqDestroyRequest' => [
                'ids' => 'ID'
            ],
            'faqCreateRequest' => [
                'status' => 'Status',
                'rating' => 'Rating',
                'schoolId' => 'School ID',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'faqController' => [
                'create' => [
                    'log' => 'Create a faq.'
                ],
                'update' => [
                    'log' => 'Update the faq.'
                ],
                'destroy' => [
                    'log' => 'Destroy the faq.'
                ],
            ],
        ],
    ]
];
