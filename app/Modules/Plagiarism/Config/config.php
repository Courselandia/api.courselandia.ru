<?php

return [
    'driver' => env('PLAGIARISM_DRIVER'),
    'services' => [
        'textRu' => [
            'token' => env('TEXT_RU_TOKEN'),
        ],
    ],
];
