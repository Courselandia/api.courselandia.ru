<?php

return [
    'requests' => [
        'admin' => [
            'courseReadRequest' => [
                'sorts' => 'Сортировка',
                'start' => 'Начало',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'courseDestroyRequest' => [
                'ids' => 'ID'
            ],
            'courseCreateRequest' => [
                'image' => 'Изображение',
                'schoolId' => 'ID школы',
                'status' => 'Статус',
                'directions' => 'Направления',
                'professions' => 'Профессии',
                'categories' => 'Категории',
                'skills' => 'Навыки',
                'teachers' => 'Учителя',
                'tools' => 'Инструменты',
                'levels' => 'Уровни',
                'learns' => 'Чему научитесь',
                'employments' => 'Трудоустройство',
                'features' => 'Особенности',
                'language' => 'Язык',
                'rating' => 'Рейтинг',
                'price' => 'Цена',
                'price_discount' => 'Цена со скидкой',
                'price_recurrent_price' => 'Цена по кредиту',
                'currency' => 'Валюта',
                'online' => 'Онлайн',
                'duration' => 'Продолжительность',
                'durationUnit' => 'Единица измерения продолжительности',
                'lessons_amount' => 'Количество уроков',
                'modules_amount' => 'Количество модулей',
            ],
            'courseUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'courseImageUpdateRequest' => [
                'image' => 'Изображение',
            ]
        ],
        'site' => [
            'courseReadRequest' => [
                'year' => 'Год',
                'limit' => 'Лимит',
                'page' => 'Страница',
                'path' => 'Путь',
            ],
            'courseGetRequest' => [
                'id' => 'ID',
                'link' => 'Ссылка',
            ],
        ]
    ],
    'controllers' => [
        'admin' => [
            'courseController' => [
                'create' => [
                    'log' => 'Создание публикации.'
                ],
                'update' => [
                    'log' => 'Обновление публикации.'
                ],
                'destroy' => [
                    'log' => 'Удаление публикации.'
                ],
                'destroyImage' => [
                    'log' => 'Удаление изображения публикации.'
                ]
            ],
        ],
    ]
];
