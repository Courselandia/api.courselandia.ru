<?php

return [
    'seeders' => [
        'moduleTableSeeder' => [
            'name' => 'Users',
            'components' => [
                'edit' => 'Edit',
                'info' => 'Info'
            ],
            'widgets' => [
                'newUsers' => 'New users'
            ]
        ],
        'userGroupTableSeeder' => [
            'admin' => 'Admin',
            'edit' => 'Edit',
            'user' => 'User',
        ],
        'userRoleTableSeeder' => [
            'admin' => 'Admin',
            'edit' => 'Edit',
            'manager' => 'Manager',
        ]
    ]
];
