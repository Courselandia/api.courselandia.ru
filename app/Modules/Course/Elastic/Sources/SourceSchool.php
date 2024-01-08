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
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;

/**
 * Экспортирование школ.
 */
class SourceSchool extends Source
{
    /**
     * Возвращает название индекса.
     *
     * @return string Название индекса.
     */
    public function name(): string
    {
        return 'schools';
    }

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
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
            $item = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($item) {
                $data = [
                    'index' => $this->name(),
                    'body' => $item->toArray(),
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

            $schools = School::whereNotIn('id', $activeIds)
                ->get()
                ?->toArray();

            foreach ($schools as $school) {
                Elasticsearch::deleteByQuery([
                    'index' => $this->name(),
                    'body' => [
                        'query' => [
                            'match' => [
                                'id' => $school['id'],
                            ],
                        ],
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
                            'name' => [
                                'type' => 'text',
                                'analyzer' => 'russian',
                                'fields' => [
                                    'raw' => [
                                        'type' => 'keyword',
                                    ],
                                ],
                            ],
                            'header' => [
                                'type' => 'text',
                                'analyzer' => 'russian',
                            ],
                            'link' => [
                                'type' => 'keyword',
                            ],
                            'text' => [
                                'type' => 'text',
                                'analyzer' => 'russian',
                            ],
                            'rating' => [
                                'type' => 'integer',

                            ],
                            'site' => [
                                'type' => 'text',
                            ],
                            'image_site_id' => $imageNested,
                            'image_logo_id' => $imageNested,
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
                            'amount_courses' => [
                                'type' => 'nested',
                                'properties' => [
                                    'all' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_games' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_other' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_design' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_business' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_analytics' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_marketing' => [
                                        'type' => 'integer',
                                    ],
                                    'direction_programming' => [
                                        'type' => 'integer',
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
        return School::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value);
        })
        ->where('status', true)
        ->with('metatag')
        ->orderBy('name');
    }
}
