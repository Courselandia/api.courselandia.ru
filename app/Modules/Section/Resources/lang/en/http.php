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
                'level' => 'Level',
                'items' => 'Items',
                'id' => 'ID',
                'type' => 'Type',
            ]
        ],
        'site' => [
            'sectionLinkRequest' => [
                'items' => 'Items',
                'level' => 'Level',
                'free' => 'Free',
                'type' => 'Type',
                'link' => 'Link',
            ],
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
    ],
];
