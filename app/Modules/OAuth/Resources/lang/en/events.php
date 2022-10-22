<?php

return [
    "listeners" => [
        "oAuthClientEloquentListener" => [
            "existError" => "You cannot add the client because it is already in the database."
        ],
        "oAuthTokenEloquentListener" => [
            "existError" => "You cannot add the token because it is already in the database."
        ]
    ]
];
