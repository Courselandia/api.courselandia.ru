<?php

return [
    'requests' => [
        'admin' => [
            'courseReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
                'rating' => 'Rating',
                'price' => 'Price',
                'online' => 'Online',
                'employment' => 'Employment',
                'duration' => 'Duration',
            ],
            'courseDestroyRequest' => [
                'ids' => 'ID'
            ],
            'courseCreateRequest' => [
                'image' => 'Image',
                'schoolId' => 'School ID',
                'status' => 'Status',
                'directions' => 'Directions',
                'professions' => 'Professions',
                'categories' => 'Categories',
                'skills' => 'Skills',
                'teachers' => 'Teachers',
                'tools' => 'Tools',
                'processes' => 'Processes',
                'levels' => 'Levels',
                'learns' => 'Learns',
                'employments' => 'Employments',
                'features' => 'Features',
                'icon' => 'Icon',
                'text' => 'Text',
                'language' => 'Language',
                'rating' => 'Rating',
                'price' => 'Price',
                'priceOld' => 'Discount price',
                'priceRecurrentPrice' => 'Price with subscription',
                'currency' => 'Currency',
                'online' => 'Online',
                'duration' => 'Duration',
                'durationUnit' => 'Duration Unit',
                'lessonsAmount' => 'Lessons amount',
                'modulesAmount' => 'Modules amount',
                'program' => 'Course program',
            ],
            'courseUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'courseImageUpdateRequest' => [
                'image' => 'Image',
            ],
            'courseReadFavoritesRequest' => [
                'ids' => 'IDs',
            ],
        ],
        'site' => [
            'courseReadRequest' => [
                'year' => 'Year',
                'limit' => 'Limit',
                'page' => 'Page',
                'path' => 'Path',
                'withCategories' => 'With categories',
                'withCount' => 'Amount',
                'openedSchools' => 'Opened Schools',
                'openedCategories' => 'Opened Categories',
                'openedProfessions' => 'Opened Professions',
                'openedTeachers' => 'Opened Teachers',
                'openedSkills' => 'Opened Skills',
                'openedTools' => 'Opened Tools',
            ],
            'courseGetRequest' => [
                'id' => 'ID',
                'link' => 'Link',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'courseController' => [
                'create' => [
                    'log' => 'Create a course.'
                ],
                'update' => [
                    'log' => 'Update the course.'
                ],
                'destroy' => [
                    'log' => 'Destroy the course.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the course.'
                ]
            ],
        ],
    ]
];
