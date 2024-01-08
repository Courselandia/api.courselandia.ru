<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic\Sources;

use Elasticsearch;
use App\Modules\Course\Elastic\Source;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;

/**
 * Экспортирование курсов.
 */
class SourceCourse extends Source
{
    /**
     * Возвращает название индекса.
     *
     * @return string Название индекса.
     */
    public function name(): string
    {
        return 'courses';
    }

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Course::where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            })
            ->count();
    }

    /**
     * Запуск процесса экспортирования.
     *
     * @return void
     */
    public function export(): void
    {
        $this->addMapping();
        $count = $this->count();
        $query = $this->getQuery();

        for ($i = 0; $i < $count; $i++) {
            $course = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first()
                ?->toArray();

            if ($course) {
                $data = [
                    'index' => $this->name(),
                    'body' => $course,
                ];

                Elasticsearch::index($data);

                $this->fireEvent('export');
            }
        }
    }

    /**
     * Запуск процесса удаления старых записей.
     *
     * @return void
     */
    public function delete(): void
    {
        $exist = Elasticsearch::indices()->exists([
            'index' => $this->name(),
        ]);

        if ($exist) {
            $activeIds = $this->getQuery()
                ->get()
                ->pluck('id');

            $courses = Course::whereNotIn('id', $activeIds)
                ->get()
                ?->toArray();

            foreach ($courses as $course) {
                Elasticsearch::deleteByQuery([
                    'index' => $this->name(),
                    'body' => [
                        'query' => [
                            'match' => [
                                'id' => $course['id'],
                            ],
                        ]
                    ],
                ]);
            }
        }
    }

    /**
     * Проводим маппинг данным.
     *
     * @return void
     */
    private function addMapping(): void
    {
        $exist = Elasticsearch::indices()->exists([
            'index' => $this->name(),
        ]);

        if (!$exist) {
            $propertyNested = [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        "type" => 'integer',
                    ],
                    'name' => [
                        'type' => 'keyword',
                    ],
                    'link' => [
                        'type' => 'keyword',
                    ],
                ],
            ];

            $imageNested = [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        "type" => 'text',
                    ],
                    'width' => [
                        'type' => 'integer',
                    ],
                    'height' => [
                        'type' => 'integer',
                    ],
                    'path' => [
                        'type' => 'text',
                    ],
                ],
            ];

            $data = [
                'index' => $this->name(),
                'body' => [
                    'mappings' => [
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                            ],
                            'school_id' => [
                                'type' => 'integer',
                            ],
                            'name' => [
                                'type' => 'text',
                                'analyzer' => 'russian',
                                'fields' => [
                                    'raw' => [
                                        'type' => 'keyword',
                                    ],
                                ],
                            ],
                            'text' => [
                                'type' => 'text',
                                'analyzer' => 'russian',
                            ],
                            'rating' => [
                                'type' => 'integer',
                            ],
                            'price' => [
                                'type' => 'float',
                            ],
                            'price_old' => [
                                'type' => 'float',
                            ],
                            'price_recurrent' => [
                                'type' => 'float',
                            ],
                            'online' => [
                                'type' => 'integer',
                            ],
                            'professions' => $propertyNested,
                            'categories' => $propertyNested,
                            'skills' => $propertyNested,
                            'teachers' => $propertyNested,
                            'tools' => $propertyNested,
                            'school' => $propertyNested,
                            'directions' => $propertyNested,
                            'image_big_id' => $imageNested,
                            'image_middle_id' => $imageNested,
                            'image_small_id' => $imageNested,
                            'levels' => [
                                'type' => 'nested',
                                'properties' => [
                                    'id' => [
                                        'type' => 'integer',
                                    ],
                                    'level' => [
                                        'type' => 'keyword',
                                    ],
                                ],
                            ],
                            'metatag' => [
                                'type' => 'nested',
                                'properties' => [
                                    'description' => [
                                        'type' => 'text',
                                        'analyzer' => 'russian',
                                    ],
                                    'keywords' => [
                                        'type' => 'text',
                                        'analyzer' => 'russian',
                                    ],
                                    'title' => [
                                        'type' => 'text',
                                        'analyzer' => 'russian',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ];

            Elasticsearch::indices()->create($data);
        }
    }

    /**
     * Получаем запрос для выборки.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Course::with([
            'professions' => function ($query) {
                $query->where('status', true);
            },
            'professions.salaries' => function ($query) {
                $query->where('status', true);
            },
            'categories' => function ($query) {
                $query->where('status', true);
            },
            'skills' => function ($query) {
                $query->where('status', true);
            },
            'teachers' => function ($query) {
                $query->where('status', true);
            },
            'tools' => function ($query) {
                $query->where('status', true);
            },
            'processes' => function ($query) {
                $query->where('status', true);
            },
            'school' => function ($query) {
                $query->where('status', true);
            },
            'school.faqs' => function ($query) {
                $query->where('status', true);
            },
            'directions' => function ($query) {
                $query->where('status', true);
            },
            'directions.categories',
            'metatag',
            'levels',
            'learns',
            'employments',
            'features',
            'professions.salaries'
        ])
        ->where('status', Status::ACTIVE->value)
        ->whereHas('school', function ($query) {
            $query->where('status', true);
        })
        ->orderBy('courses.id');
    }
}
