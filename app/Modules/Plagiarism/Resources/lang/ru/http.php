<?php

return [
    'controllers' => [
        'admin' => [
            'plagiarismController' => [
                'request' => [
                    'log' => 'Отправлен запрос на анализ текста.'
                ],
            ],
        ],
    ],
    'requests' => [
        'admin' => [
            'plagiarismAnalyzeRequest' => [
                'text' => 'Текст',
            ],
        ],
    ],
];
