<?php

return [
    'middleware' => [
        'allowGuest' => [
            'text' => 'Доступ к этой части приложения запрещено, пожалуйста выйдите из системы.',
            'label' => 'Авторизован.'
        ],
        'allowLimit' => [
            'text' => 'Доступ к этой части приложения ограничен.',
            'label' => 'Ограничено.'
        ],
        'allowOAuth' => [
            'label' => 'Не авторизован.',
            'noHeader' => 'Не авторизован: Нет заголовка.'
        ],
        'allowPaid' => [
            'message' => [
                'true' => 'Доступ к этой части приложения недопустим потому что выбранный вами тариф не оплачен.',
                'false' => 'Доступ к этой части приложения недопустим потому что выбранный вами тариф оплачен.
        '
            ],
            'label' => [
                'true' => 'Не оплачен.',
                'false' => 'Оплачен.'
            ]
        ],
        'allowRole' => [
            'message' => 'Доступ к этой части приложения ограничен.',
            'label' => 'Ограничено'
        ],
        'allowPage' => [
            'message' => 'Доступ к этой части приложения ограничен.',
            'label' => 'Ограничено'
        ],
        'allowPageUpdate' => [
            'message' => 'Доступ к этой части приложения ограничен.',
            'label' => 'Ограничено'
        ],
        'allowGroup' => [
            'message' => 'Доступ к этой части приложения ограничен.',
            'label' => 'Ограничено.'
        ],
        'allowSection' => [
            'message' => 'Доступ к этой части приложения недопустим.',
            'label' => 'Ограничен.'
        ],
        'allowTrial' => [
            'message' => [
                'true' => 'Доступ к этой части приложения только для пользователей с пробным периодом.',
                'false' => 'Доступ к этой части приложения только для пользователей с оплаченным периодом.'
            ],
            'label' => [
                'true' => 'Оплаченный.',
                'false' => 'Пробный.'
            ]
        ],
        'allowUser' => [
            'message' => 'Доступ к этой части приложения был окончен, пожалуйста авторизуйтесь.',
            'label' => 'Не авторизован.'
        ],
        'allowAdmin' => [
            'message' => 'Доступ к этой части приложения был окончен, пожалуйста авторизуйтесь.',
            'label' => 'Не авторизован.'
        ],
        'allowVerified' => [
            'message' => [
                'true' => 'Доступ к этой части приложения только для верифицированных пользователей.',
                'false' => 'Доступ к этой части приложения только для не верифицированных пользователей.'
            ],
            'label' => [
                'true' => 'Не верифицированный.',
                'false' => 'Верифицированный.'
            ]
        ],
        'allowSubscribed' => [
            'message' => [
                'true' => 'Доступ к этой части приложения только для подписанных пользователей.',
                'false' => 'Доступ к этой части приложения только для не отписавшихся пользователей.'
            ],
            'label' => [
                'true' => 'Не подписанный.',
                'false' => 'Подписанный.'
            ]
        ]
    ],
    'requests' => [
        'accessApiClientRequest' => [
            'login' => 'Логин',
            'password' => 'Пароль',
            'remember' => 'Заполнить'
        ],
        'accessApiRefreshRequest' => [
            'refreshToken' => 'Токен обновления'
        ],
        'accessApiTokenRequest' => [
            'secret' => 'Секретный ключ'
        ],
        'site' => [
            'accessForgetRequest' => [
                'login' => 'Логин'
            ],
            'accessPasswordRequest' => [
                'passwordCurrent' => 'Текущий пароль',
                'password' => 'Пароль'
            ],
            'accessResetCheckRequest' => [
                'code' => 'Код'
            ],
            'accessResetRequest' => [
                'code' => 'Код',
                'password' => 'Пароль'
            ],
            'accessSignInRequest' => [
                'login' => 'Логин',
                'password' => 'Пароль',
                'remember' => 'Запомнить'
            ],
            'accessSignUpRequest' => [
                'login' => 'Логин',
                'password' => 'Пароль',
                'firstName' => 'Имя',
                'secondName' => 'Фамилия',
                'company' => 'Компания',
                'phone' => 'Телефон'
            ],
            'accessSocialRequest' => [
                'uid' => 'UID',
                'social' => 'Социальная сеть',
                'login' => 'Логин'
            ],
            'accessVerifiedRequest' => [
                'code' => 'Код'
            ]
        ]
    ],
    'controllers' => [
        'site' => [
            'accessController' => [
                'social' => [
                    'log' => 'Авторизация через социальные сети.'
                ],
                'signIn' => [
                    'log' => 'Пользователь авторизовался.'
                ],
                'signUp' => [
                    'log' => 'Новый пользователь зарегистрировался.'
                ],
                'verified' => [
                    'log' => 'Пользователь верифицировался.'
                ],
                'verify' => [
                    'log' => 'Письмо для верификации было выслано.'
                ],
                'forget' => [
                    'log' => 'Письмо для восстановления пароля было выслано.'
                ],
                'update' => [
                    'log' => 'Обновление пользователя.'
                ],
                'password' => [
                    'log' => 'Пароль был изменен пользователем.'
                ],
            ]
        ]
    ]
];
