<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use App\Modules\Course\Enums\Status;
use App\Modules\Direction\Models\Direction;
use Generator;
use App\Modules\Core\Sitemap\Item;
use App\Modules\Core\Sitemap\Part;
use Illuminate\Database\Query\Builder;

/**
 * Генератор для направлений.
 */
class PartDirection extends Part
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
                $item->path = 'courses/direction/' . $result['link'];

                yield $item;
            }
        }
    }

    private function getQuery(): Builder
    {
        return Direction::select([
            'directions.link',
        ])
        ->whereHas('courses', function ($query) {
            $query->select([
                'courses.id',
            ])
            ->where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            });
        })
        ->where('status', true)
        ->orderBy('weight');
    }
}
