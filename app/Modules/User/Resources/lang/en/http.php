<?php

return [
    'requests' => [
        'admin' => [
            'user' => [
                'userReadRequest' => [
                    'sorts' => 'Sorts',
                    'offset' => 'Offset',
                    'limit' => 'Limit',
                    'filters' => 'Filters',
                    'status' => 'Status',
                ],
                'userDestroyRequest' => [
                    'ids' => 'ID'
                ],
                'userCreateRequest' => [
                    'image' => 'Image',
                    'invitation' => 'Invitation',
                    'role' => 'Role',
                    'verified' => 'Verified',
                    'two_factor' => 'Two factor',
                    'status' => 'Status',
                ],
                'userUpdateStatusRequest' => [
                    'status' => 'Status'
                ],
                'userImageUpdateRequest' => [
                    'image' => 'Image'
                ],
                'userProfileUpdateRequest' => [
                    'image' => 'Image'
                ]
            ],
            'userAnalytics' => [
                'userAnalyticsNewUsersRequest' => [
                    'group' => 'Group',
                    'date' => 'Date',
                    'dateFrom' => 'Date from',
                    'dateTo' => 'Date to'
                ],
            ],
            'config' => [
                'userConfigUpdateRequest' => [
                    'configs' => 'Configs'
                ]
            ]
        ],
    ],
    'controllers' => [
        'admin' => [
            'userController' => [
                'get' => [
                    'log' => 'Get the user.'
                ],
                'read' => [
                    'log' => 'Read users.'
                ],
                'create' => [
                    'log' => 'Create a user.',
                ],
                'update' => [
                    'log' => 'Update the user.',
                ],
                'password' => [
                    'log' => 'Update the password of the user.',
                ],
                'destroy' => [
                    'log' => 'Destroy the user.',
                ]
            ],
            'userConfigController' => [
                'get' => [
                    'log' => 'Get the configs of the user.'
                ],
                'update' => [
                    'log' => 'Update the configs of the user.',
                ],
            ],
            'userImageController' => [
                'get' => [
                    'log' => 'Get the image of the user.'
                ],
                'update' => [
                    'log' => 'Update the image of the user.',
                ],
                'destroy' => [
                    'log' => 'Destroy the image of the user.',
                ]
            ],
        ]
    ]
];
