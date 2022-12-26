<?php

return [
    "listeners" => [
        "oAuthClientEloquentListener" => [
            "existError" => "Вы не можете добавить клиента, потому что он уже есть в базе данных."
        ],
        "oAuthTokenEloquentListener" => [
            "existError" => "Вы не можете добавить токен, потому что он уже есть в базе данных."
        ]
    ]
];
