<?php

return [
    'requests' => [
        'admin' => [
            'sectionReadRequest' => [
                'sorts' => 'Сортировка',
                'offset' => 'Отступ',
                'limit' => 'Лимит',
                'filters' => 'Фильтр',
                'status' => 'Статус',
            ],
            'sectionDestroyRequest' => [
                'ids' => 'ID'
            ],
            'sectionUpdateStatusRequest' => [
                'status' => 'Статус',
            ],
            'sectionCreateRequest' => [
                'status' => 'Статус',
                'level' => 'Уровень',
                'items' => 'Элементы',
                'id' => 'ID',
                'type' => 'Тип',
            ]
        ],
        'site' => [
            'sectionLinkRequest' => [
                'items' => 'Элементы',
                'level' => 'Уровень',
                'free' => 'Признак бесплатности',
                'type' => 'Тип элемента раздела',
                'link' => 'Ссылка элемента',
            ],
        ],
    ],
    'controllers' => [
        'admin' => [
            'sectionController' => [
                'create' => [
                    'log' => 'Создание раздела.'
                ],
                'update' => [
                    'log' => 'Обновление раздела.'
                ],
                'destroy' => [
                    'log' => 'Удаление раздела.'
                ],
            ],
        ],
    ]
];
