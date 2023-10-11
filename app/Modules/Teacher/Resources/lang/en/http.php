<?php

return [
    'requests' => [
        'admin' => [
            'teacherReadRequest' => [
                'sorts' => 'Sorts',
                'offset' => 'Offset',
                'limit' => 'Limit',
                'filters' => 'Filters',
                'status' => 'Status',
            ],
            'teacherDestroyRequest' => [
                'ids' => 'ID'
            ],
            'teacherCreateRequest' => [
                'image' => 'Image',
                'imageCroppedOptions' => 'Image Cropped Options',
                'directions' => 'Directions',
                'schools' => 'Schools',
                'rating' => 'Rating',
                'copied' => 'Copied',
                'status' => 'Status',
                'experiences' => 'Experiences',
                'socialMedias' => 'Social Medias',
            ],
            'teacherUpdateStatusRequest' => [
                'status' => 'Status',
            ],
            'teacherImageUpdateRequest' => [
                'image' => 'Image',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'teacherController' => [
                'create' => [
                    'log' => 'Create a teacher.'
                ],
                'update' => [
                    'log' => 'Update the teacher.'
                ],
                'destroy' => [
                    'log' => 'Destroy the teacher.'
                ],
                'detachCourses' => [
                    'log' => 'Detach courses from the teacher.'
                ],
                'destroyImage' => [
                    'log' => 'Destroy the image of the teacher.'
                ]
            ],
        ],
    ]
];
