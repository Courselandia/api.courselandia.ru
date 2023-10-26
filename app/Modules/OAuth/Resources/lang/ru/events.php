<?php

return [
    "listeners" => [
        "oAuthTokenEloquentListener" => [
            "existError" => "Вы не можете добавить токен, потому что он уже есть в базе данных."
        ]
    ]
];
