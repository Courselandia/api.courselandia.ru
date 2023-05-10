<?php

return [
    'controllers' => [
        'admin' => [
            'writerController' => [
                'write' => [
                    'log' => 'Отправлен запрос на написания текста.'
                ],
            ],
        ],
    ],
    'requests' => [
        'admin' => [
            'writerWriteRequest' => [
                'request' => 'Запрос на написания текста',
            ],
        ],
    ],
];
