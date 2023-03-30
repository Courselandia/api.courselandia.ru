<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Generator;
use App\Modules\Core\Sitemap\Item;
use App\Modules\Course\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Course\Models\Course;

/**
 * Генератор для курсов.
 */
class PartCourse extends PartDirection
{
    /**
     * Вернет количество генерируемых элементов.
     *
     * @return int Количество элементов.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Генерация элемента.
     *
     * @return Generator<Item> Генерируемый элемент.
     */
    public function generate(): Generator
    {
        $count = $this->count();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                $item = new Item();
                $item->path = 'courses/show/' . $result['school']['link'] . '/' . $result['link'];

                yield $item;
            }
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Course::select([
            'id',
            'school_id',
            'link',
        ])
        ->with([
            'school' => function ($query) {
                $query->select([
                    'schools.id',
                    'schools.link',
                ])->where('status', true);
            },
        ])
        ->where('status', Status::ACTIVE->value)
        ->whereHas('school', function ($query) {
            $query->where('status', true);
        });
    }
}
