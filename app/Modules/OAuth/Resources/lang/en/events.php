<?php

return [
    "listeners" => [
        "oAuthTokenEloquentListener" => [
            "existError" => "You cannot add the token because it is already in the database."
        ]
    ]
];
