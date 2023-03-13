<?php

return [
    'requests' => [
        'admin' => [
            'courseReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
                'rating' => 'Рейтинг',
                'price' => 'Цена',
                'online' => 'Онлайн',
                'employment' => 'Трудоустройство',
                'duration' => 'Продолжительность',
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
                'processes' => 'Как проходит обучение',
                'levels' => 'Уровни',
                'learns' => 'Чему научитесь',
                'employments' => 'Трудоустройство',
                'features' => 'Особенности',
                'icon' => 'Иконка',
                'text' => 'Текст',
                'language' => 'Язык',
                'rating' => 'Рейтинг',
                'price' => 'Цена',
                'priceOld' => 'Старая цена',
                'priceRecurrentPrice' => 'Цена по кредиту',
                'currency' => 'Валюта',
                'online' => 'Онлайн',
                'duration' => 'Продолжительность',
                'durationUnit' => 'Единица измерения продолжительности',
                'lessonsAmount' => 'Количество уроков',
                'modulesAmount' => 'Количество модулей',
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
                'withCategories' => 'С категориями',
                'withCount' => 'Количество',
                'openedSchools' => 'Школы открыты',
                'openedCategories' => 'Категории открыты',
                'openedProfessions' => 'Профессии открыты',
                'openedTeachers' => 'Учителя открыты',
                'openedSkills' => 'Навыки открыты',
                'openedTools' => 'Инструменты открыты',
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
