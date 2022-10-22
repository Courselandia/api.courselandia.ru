<?php

return [
    'site' => [
        'update' => [
            'userPipe' => [
                'notExistUser' => "The user doesn't exist or not find it."
            ]
        ],
        'verified' => [
            'checkPipe' => [
                'notExistUser' => "The user doesn't exist or not find it.",
                'notExistCode' => "The verification code doesn't exist.",
                'notCorrectCode' => "The verification code is not correct.",
                'userIsVerified' => "The user has been already verified."
            ]
        ],
        'signUp' => [
            'notExistGroup' => "The group :name doesn't exist or not find it.",
        ]
    ]
];
