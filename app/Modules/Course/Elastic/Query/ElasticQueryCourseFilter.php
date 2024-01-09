<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic\Query;

use function PHPUnit\Framework\isEmpty;

/**
 * Построитель запросов к Elasticsearch для фильтрации курсов.
 */
class ElasticQueryCourseFilter extends ElasticQuery
{
    /**
     * Сортировки.
     *
     * @var array|null
     */
    private ?array $sorts = null;

    /**
     * Фильтры.
     *
     * @var array|null
     */
    private ?array $filters = null;

    /**
     * Агрегация.
     *
     * @var array|null
     */
    private ?array $aggs = null;

    /**
     * Получить запрос для фильтрации.
     *
     * @return array Запрос для фильтрации.
     */
    public function getBody(): array
    {
        $body = parent::getBody();

        if ($this->sorts) {
            $body['sort'] = $this->sorts;
        }

        if ($this->filters) {
            $body['query'] = [
                'bool' => [
                    'must' => $this->filters
                ]
            ];
        } else {
            $body['query'] = [
                'match_all' => (object)[],
            ];
        }

        if ($this->aggs) {
            $body['aggs'] = [
                'courses' => [
                    'global' => (object)[],
                    'aggs' => $this->aggs,
                ],
            ];
        }

        return $body;
    }

    /**
     * Установка сортировки.
     *
     * @param array $sorts Сортировка.
     * @param array $filters Фильтрация.
     *
     * @return $this
     */
    public function setSorts(array $sorts, array $filters): self
    {
        if (
            array_key_exists('relevance', $sorts)
            || (
                isset($filters['search'])
                && $filters['search']
            )
        ) {
            $this->sorts[] = [
                '_score' => 'DESC'
            ];
        } else if (!isEmpty($sorts)) {
            $this->sorts = [];

            foreach ($sorts as $field => $order) {
                if ($field === 'name') {
                    $this->sorts[] = [
                        $field => $order
                    ];
                } else {
                    $this->sorts[] = [
                        $field . '.raw' => $order
                    ];
                }
            }
        } else {
            $this->sorts[] = [
                'name.raw' => 'asc'
            ];
        }

        return $this;
    }

    /**
     * Установка фильтров.
     *
     * @param array $filters Фильтры.
     *
     * @return $this
     */
    public function setFilters(array $filters): self
    {
        $this->filters = [];

        foreach ($filters as $field => $value) {
            if (
                $field === 'skills-id'
                || $field === 'professions-id'
                || $field === 'categories-id'
                || $field === 'teachers-id'
                || $field === 'tools-id'
                || $field === 'school-id'
                || $field === 'directions-id'
            ) {
                $this->filters[] = [
                    'terms' => [
                        $this->getPath($field) . '.id' => is_array($value) ? $value : [$value],
                    ],
                ];
            } else if ($field === 'rating') {
                $this->filters[] = [
                    'range' => [
                        'rating' => [
                            'gte' => $value,
                        ],
                    ],
                ];
            } else if ($field === 'price') {
                $this->filters[] = [
                    'range' => [
                        'price' => [
                            'gte' => $value[0],
                            'lte' => $value[1],
                        ],
                    ],
                ];
            } else if ($field === 'credit') {
                $this->filters[] = [
                    'range' => [
                        'price_recurrent' => [
                            'gt' => 0,
                        ],
                    ],
                ];
            } else if ($field === 'free') {
                $this->filters[] = [
                    'terms' => [
                        'price' => [0],
                    ],
                ];
            } else if ($field === 'duration') {
                $this->filters[] = [
                    'range' => [
                        'duration' => [
                            'gte' => $value[0],
                            'lte' => $value[1],
                        ],
                    ],
                ];
            } else if ($field === 'online') {
                $this->filters[] = [
                    'term' => [
                        'online' => 1,
                    ],
                ];
            } else if ($field === 'levels-level') {
                $this->filters[] = [
                    'terms' => [
                        'levels.level' => is_array($value) ? $value : [$value],
                    ],
                ];
            } else if ($field === 'search') {
                $this->filters[] = [
                    'match' => [
                        'name' => $value,
                    ],
                ];

                $this->filters[] = [
                    'match' => [
                        'text' => $value,
                    ],
                ];
            } else if ($field === 'ids') {
                $this->filters[] = [
                    'terms' => [
                        'id' => is_array($value) ? $value : [$value],
                    ],
                ];
            }
        }

        return $this;
    }

    /**
     * Получение пути к вложенной коллекции дял поиска.
     *
     * @param string $filterField Поле для фильтрации.
     *
     * @return string|null Вернет название вложенной коллекции.
     */
    private function getPath(string $filterField): ?string
    {
        if ($filterField === 'skills-id') {
            return 'skills';
        }

        if ($filterField === 'professions-id') {
            return 'professions';
        }

        if ($filterField === 'categories-id') {
            return 'categories';
        }

        if ($filterField === 'teachers-id') {
            return 'teachers';
        }

        if ($filterField === 'tools-id') {
            return 'tools';
        }

        if ($filterField === 'school-id') {
            return 'school';
        }

        if ($filterField === 'directions-id') {
            return 'directions';
        }

        return null;
    }

    /**
     * Получение агрегационного фильтра
     *
     * @param array $filters Фильтры.
     *
     * @return array Запрос для агрегационного фильтра.
     */
    private function getAggFilters(array $filters, string $excludeField): array
    {
        $result = [];

        foreach ($filters as $field => $value) {
            if ($excludeField === $field || $this->getPath($field) === $excludeField) {
                continue;
            }

            if (
                $field === 'skills-id'
                || $field === 'professions-id'
                || $field === 'categories-id'
                || $field === 'teachers-id'
                || $field === 'tools-id'
                || $field === 'school-id'
                || $field === 'directions-id'
            ) {
                $result[] = [
                    'nested' => [
                        'path' => $this->getPath($field),
                        'query' => [
                            'bool' => [
                                'must' => [
                                    'terms' => [
                                        $this->getPath($field) . '.id' => is_array($value) ? $value : [$value]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            } else if ($field === 'rating') {
                $result[] = [
                    'range' => [
                        'rating' => [
                            'gte' => $value,
                        ],
                    ]
                ];
            } else if ($field === 'price') {
                $result[] = [
                    'range' => [
                        'price' => [
                            'gte' => $value[0],
                            'lte' => $value[1],
                        ],
                    ]
                ];
            } else if ($field === 'credit') {
                $result[] = [
                    'range' => [
                        'price_recurrent' => [
                            'gt' => 0,
                        ],
                    ]
                ];
            } else if ($field === 'free') {
                $result[] = [
                    'terms' => [
                        'price' => [0],
                    ],
                ];
            } else if ($field === 'duration') {
                $result[] = [
                    'range' => [
                        'duration' => [
                            'gte' => $value[0],
                            'lte' => $value[1],
                        ],
                    ]
                ];
            } else if ($field === 'online') {
                $result[] = [
                    'term' => [
                        'online' => 1,
                    ]
                ];
            } else if ($field === 'levels-level') {
                $result[] = [
                    'terms' => [
                        'levels.level' => is_array($value) ? $value : [$value],
                    ]
                ];
            } else if ($field === 'search') {
                $result[] = [
                    'match' => [
                        'name' => $value,
                    ]
                ];

                $result[] = [
                    'match' => [
                        'text' => $value,
                    ]
                ];
            } else if ($field === 'ids') {
                $result[] = [
                    'terms' => [
                        'id' => is_array($value) ? $value : [$value],
                    ]
                ];
            }
        }

        return $result;
    }

    /**
     * Получить агрегацию.
     *
     * @param string $name Название агрегации.
     * @param array $includeFields Дополнительные поля, которые нужно получить в агрегации.
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return array Запрос для агрегации.
     */
    private function getAgg(string $name, array $includeFields, array $filters = [], int $size = null): array
    {
        $aggFilters = $this->getAggFilters($filters, $name);

        return [
            'filter' => [
                'bool' => [
                    'must' => $aggFilters,
                ]
            ],
            'aggs' => [
                $name => [
                    'nested' => [
                        'path' => $name,
                    ],
                    'aggs' => [
                        $name => [
                            'terms' => [
                                'field' => $name . '.name',
                                'size' => $size ?? 10000,
                                'min_doc_count' => 0,
                            ],
                            'aggs' => [
                                'fields' => [
                                    'top_hits' => [
                                        'size' => 1,
                                        '_source' => [
                                            'include' => $includeFields,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * Получить агрегацию направлений.
     *
     * @param array $filters Фильтры.
     * @param bool $withCategories Добавить категории.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggDirections(array $filters = [], bool $withCategories = false, int $size = null): self
    {
        $this->aggs['directions'] = $this->getAgg('directions',
            [
                'directions.id',
                'directions.name',
                'directions.link',
                'directions.weight',
                ...($withCategories ? ['directions.categories'] : [])
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию категорий.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggCategories(array $filters = [], int $size = null): self
    {
        $this->aggs['categories'] = $this->getAgg('categories',
            [
                'categories.id',
                'categories.link',
                'categories.name',
                'categories.link',
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию профессий.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggProfessions(array $filters = [], int $size = null): self
    {
        $this->aggs['professions'] = $this->getAgg('professions',
            [
                'professions.id',
                'professions.link',
                'professions.name',
                'professions.link',
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию школ.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggSchools(array $filters = [], int $size = null): self
    {
        $this->aggs['schools'] = $this->getAgg('schools',
            [
                'schools.id',
                'schools.link',
                'schools.name',
                'schools.link',
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию школ.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggSkills(array $filters = [], int $size = null): self
    {
        $this->aggs['skills'] = $this->getAgg('skills',
            [
                'skills.id',
                'skills.link',
                'skills.name',
                'skills.link',
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию учителей.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggTeacher(array $filters = [], int $size = null): self
    {
        $this->aggs['teachers'] = $this->getAgg('teachers',
            [
                'teachers.id',
                'teachers.link',
                'teachers.name',
                'teachers.link',
            ], $filters, $size
        );

        return $this;
    }

    /**
     * Получить агрегацию инструментов.
     *
     * @param array $filters Фильтры.
     * @param int $size Количество агрегаций.
     *
     * @return $this
     */
    public function addAggTools(array $filters = [], int $size = null): self
    {
        $this->aggs['tools'] = $this->getAgg('tools',
            [
                'tools.id',
                'tools.link',
                'tools.name',
                'tools.link',
            ], $filters, $size
        );

        return $this;
    }
}
