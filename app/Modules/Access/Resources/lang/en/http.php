<?php

return [
    'middleware' => [
        'allowGuest' => [
            'text' => 'Access to this part of the application forbidden, you have to log out.',
            'label' => 'Authorized.'
        ],
        'allowLimit' => [
            'text' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowAdmin' => [
            'text' => 'Access to this part of the application is not allowed.',
            'label' => 'Unauthorized.'
        ],
        'allowOAuth' => [
            'label' => 'Unauthorized.',
            'noHeader' => 'Unauthorized: No header.'
        ],
        'allowPaid' => [
            'message' => [
                'true' => 'Access to this part of the application is not allowed because of the unpaid plan.',
                'false' => 'Access to this part of the application is not allowed because of the paid plan.'
            ],
            'label' => [
                'true' => 'Unpaid.',
                'false' => 'Paid.'
            ]
        ],
        'allowRole' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowPage' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowPageUpdate' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowGroup' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowCompany' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowSection' => [
            'message' => 'Access to this part of the application is not allowed.',
            'label' => 'Limited.'
        ],
        'allowTrial' => [
            'message' => [
                'true' => 'Access to this part of the application only for the trial version.',
                'false' => 'Access to this part of the application is not allowed for the trial version.'
            ],
            'label' => [
                'true' => 'Paid.',
                'false' => 'Trial.'
            ]
        ],
        'allowUser' => [
            'message' => 'Access to this part of the application has been ended, please log in again.',
            'label' => 'Unauthorized.'
        ],
        'allowVerified' => [
            'message' => [
                'true' => 'Access to this part of the application only for a verified user.',
                'false' => 'Access to this part of the application only for an unverified user.'
            ],
            'label' => [
                'true' => 'Unverified.',
                'false' => 'Verified.'
            ]
        ],
        'allowSubscribed' => [
            'message' => [
                'true' => 'Access to this part of the application only for a subscribed user.',
                'false' => 'Access to this part of the application only for an unsubscribed user.'
            ],
            'label' => [
                'true' => 'Unverified.',
                'false' => 'Verified.'
            ]
        ],
    ],
    'requests' => [
        'accessApiRefreshRequest' => [
            'refreshToken' => 'Refresh token'
        ],
        'accessApiTokenRequest' => [
            'login' => 'Login',
            'password' => 'Password',
            'remember' => 'Remember',
        ],
        'site' => [
            'accessForgetRequest' => [
                'login' => 'Login'
            ],
            'accessPasswordRequest' => [
                'passwordCurrent' => 'Current password',
                'password' => 'Password'
            ],
            'accessResetCheckRequest' => [
                'code' => 'Code'
            ],
            'accessResetRequest' => [
                'code' => 'Code',
                'password' => 'Password'
            ],
            'accessSignInRequest' => [
                'login' => 'Login',
                'password' => 'Password',
                'remember' => 'Remember'
            ],
            'accessSignUpRequest' => [
                'login' => 'Login',
                'password' => 'Password',
                'firstName' => 'First name',
                'secondName' => 'Last name',
                'company' => 'Company',
                'phone' => 'phone'
            ],
            'accessSocialRequest' => [
                'uid' => 'UID',
                'social' => 'Social',
                'login' => 'Login'
            ],
            'accessVerifiedRequest' => [
                'code' => 'Code'
            ]
        ]
    ],
    'controllers' => [
        'site' => [
            'accessController' => [
                'social' => [
                    'log' => 'Log in with social network.'
                ],
                'signIn' => [
                    'log' => 'User signed in.'
                ],
                'signUp' => [
                    'log' => 'A new user signed up.'
                ],
                'verified' => [
                    'log' => 'A new user signed up.'
                ],
                'verify' => [
                    'log' => 'The email for the user verification was sent.'
                ],
                'forget' => [
                    'log' => 'The email for recovery the password was sent.'
                ],
                'update' => [
                    'log' => 'Update the user.'
                ],
                'password' => [
                    'log' => 'Password was changed by user.'
                ],
            ]
        ]
    ]
];
