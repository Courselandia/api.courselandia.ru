<?php

return [
    'accessApiTokenAction' => [
        'notExistUser' => "The user doesn't exist or not find it.",
        'passwordNotMatch' => 'The password does not match for the user.'
    ],
    'site' => [
        'accessCheckCodeResetPasswordAction' => [
            'notExistUser' => "The user doesn't exist or not find it.",
            'codeNotCorrect' => 'The recovery code is not correct.'
        ],
        'accessForgetAction' => [
            'notExistUser' => "The user doesn't exist or not find it.",
        ],
        'accessGateAction' => [
            'notExistUser' => "The user doesn't exist or not find it.",
        ],
        'accessPasswordAction' => [
            'notExistUser' => "The user doesn't exist or not find it.",
            'passwordNotMatch' => 'The password does not match for the user.'
        ],
        'accessResetAction' => [
            'notExistUser' => "The user doesn't exist or not find it."
        ],
        'accessSendEmailVerificationAction' => [
            'notExistUser' => "The user doesn't exist or not find it."
        ],
        'accessSendEmailVerificationCodeAction' => [
            'notExistUser' => "The user doesn't exist or not find it.",
            'verified' => 'The user has been already verified.'
        ]
    ]
];
