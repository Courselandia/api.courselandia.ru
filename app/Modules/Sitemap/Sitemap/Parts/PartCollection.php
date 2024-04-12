<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use Generator;
use Carbon\Carbon;
use App\Modules\Collection\Models\Collection;
use App\Modules\Sitemap\Sitemap\Item;
use Illuminate\Database\Eloquent\Builder;

/**
 * Генератор для коллекции.
 */
class PartCollection extends PartDirection
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
                $item->path = '/collections/' . $result['link'];
                $item->priority = 0.7;
                $item->lastmod = Carbon::parse($result['updated_at']);

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
        return Collection::active()
            ->orderBy('name', 'ASC');
    }
}
