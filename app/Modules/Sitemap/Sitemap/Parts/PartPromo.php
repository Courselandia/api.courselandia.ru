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
use App\Modules\Sitemap\Sitemap\Item;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\School\Models\School;

/**
 * Генератор для промокодов и акций.
 */
class PartPromo extends PartDirection
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
                $item->path = '/promos/' . $result['link'];
                $item->priority = 0.7;
                $dates = [];

                if ($result['promocodes']) {
                    foreach ($result['promocodes'] as $promocode) {
                        $dates[] = Carbon::parse($promocode['updated_at']);
                    }
                }

                if ($result['promotions']) {
                    foreach ($result['promotions'] as $promotion) {
                        $dates[] = Carbon::parse($promotion['updated_at']);
                    }
                }

                $lastmod = max($dates);

                $item->lastmod = $lastmod ?: Carbon::now();

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
        return School::active()
            ->hasCourses()
            ->with([
                'promocodes' => function ($query) {
                    $query->applicable();
                },
                'promotions' => function ($query) {
                    $query->applicable();
                },
            ])
            ->where(function ($query) {
                $query->whereHas('promocodes', function ($query) {
                    $query->applicable();
                })
                ->orWhereHas('promotions', function ($query) {
                    $query->applicable();
                });
            })
            ->orderBy('name', 'ASC');
    }
}
