<?php

return [
    'requests' => [
        'admin' => [
            'sectionReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'sectionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'sectionCreateRequest' => [
                'status' => 'Status',
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'sectionController' => [
                'create' => [
                    'log' => 'Create a section.'
                ],
                'update' => [
                    'log' => 'Update the section.'
                ],
                'destroy' => [
                    'log' => 'Destroy the section.'
                ],
            ],
        ],
        'site' => [
            'sectionLinkRequest' => [
                'links' => 'Links',
                'links.*' => 'Links',
                'level' => 'Level',
                'free' => 'Free',
            ],
        ],
    ],
];
