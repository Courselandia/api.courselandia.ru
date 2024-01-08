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
use App\Modules\Tool\Models\Tool;
use Illuminate\Database\Eloquent\Builder;

/**
 * Импортирование навыков.
 */
class SourceTool extends Source
{
    /**
     * Возвращает название индекса.
     *
     * @return string Название индекса.
     */
    public function name(): string
    {
        return 'tools';
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

            $tools = Tool::whereNotIn('id', $activeIds)
                ->get()
                ?->toArray();

            foreach ($tools as $tool) {
                Elasticsearch::deleteByQuery([
                    'index' => $this->name(),
                    'body' => [
                        'query' => [
                            'match' => [
                                'id' => $tool['id'],
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
        return Tool::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                });
        })
            ->where('status', true)
            ->with('metatag')
            ->orderBy('name');
    }
}
