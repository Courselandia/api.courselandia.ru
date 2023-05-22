<?php

return [
    'driver' => env('WRITER_DRIVER'),
    'services' => [
        'neuroTexter' => [
            'token' => env('NEURO_TEXTER'),
        ],
    ],
];
