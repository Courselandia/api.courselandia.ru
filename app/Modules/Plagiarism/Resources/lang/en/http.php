<?php

return [
    'controllers' => [
        'admin' => [
            'plagiarismController' => [
                'request' => [
                    'log' => 'Send the request for analyze the text.'
                ],
            ],
        ],
    ],
    'requests' => [
        'admin' => [
            'plagiarismAnalyzeRequest' => [
                'text' => 'Text',
            ],
        ],
    ],
];
