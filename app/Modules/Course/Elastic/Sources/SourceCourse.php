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
use Throwable;

/**
 * Экспортирование курсов.
 */
class SourceCourse extends Source
{
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
        $this->deleteAllIndexes();
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
                    'index' => 'courses',
                    'body' => $course,
                ];

                Elasticsearch::index($data);

                $this->fireEvent('export');
            }
        }
    }

    /**
     * Удаляем все индексы.
     *
     * @return void
     */
    private function deleteAllIndexes(): void
    {
        try {
            Elasticsearch::indices()->delete(['index' => 'courses']);
        } catch (Throwable $error) {

        }
    }

    /**
     * Проводим маппинг данным.
     *
     * @return void
     */
    private function addMapping(): void
    {
        $nested = [
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

        $data = [
            'index' => 'courses',
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
                        'professions' => $nested,
                        'categories' => $nested,
                        'skills' => $nested,
                        'teachers' => $nested,
                        'tools' => $nested,
                        'school' => $nested,
                        'directions' => $nested,
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
                    ],
                ]
            ]
        ];

        Elasticsearch::indices()->create($data);
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
            ->orderBy('name');
    }
}
