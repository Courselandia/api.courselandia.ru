<?php

return [
    'controllers' => [
        'admin' => [
            'writerController' => [
                'write' => [
                    'log' => 'Send the request for writing text.'
                ],
            ],
        ],
    ],
    'requests' => [
        'admin' => [
            'writerWriteRequest' => [
                'request' => 'Request',
            ],
        ],
    ],
];
