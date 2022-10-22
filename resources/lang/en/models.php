<?php

return [
    'repositoryEloquent' => [
        'record_exist' => 'The record exists.',
        'record_not_exist' => 'The record does not exists.'
    ],
    'repositoryTreeEloquent' => [
        'node_not_exist' => 'The node exists.'
    ],
    'sms' => [
        'smsCenter' => [
            'errors' => [
                'params' => 'Parameters error.',
                'access' => 'Invalid the login or password.',
                'balance' => "Insufficient funds on the Client's account.",
                'block' => 'The IP address is temporarily blocked due to frequent errors in requests.',
                'format' => 'Invalid date format.',
                'prohibited' => 'The message is prohibited (by text or by the name of the sender).',
                'invalid' => 'Invalid phone number format.',
                'unreached' => 'The message to the specified number cannot be delivered.',
                'limit' => 'Sending more than one identical request to send an SMS message or more than five identical requests to receive the cost of a message within a minute.',
                'not_exist' => 'Message not found.',
                'processing' => 'Awaiting dispatch.',
                'transmitted' => 'Transferred to the operator.',
                'expired' => 'Expired.',
                'undelivered' => 'Unable to deliver.',
                'unavailable' => 'Unavailable number.',
                'undefined' => 'Undefined number.'
            ]
        ]
    ],
    'isSort' => [
        'direction' => 'Invalid sorting direction. You provided the direction: :direction. Please provide the correct direction.',
        'relation' => 'You can only sort records by the following relations: HasOne, BelongsTo. The relation :relation is of type :type and cannot be sorted by.'
    ]
];
