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
use App\Modules\Promocode\Models\Promocode;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Sitemap\Sitemap\Item;

/**
 * Генератор для списка промокодов и акций.
 */
class PartPromos extends PartDirection
{
    /**
     * Вернет количество генерируемых элементов.
     *
     * @return int Количество элементов.
     */
    public function count(): int
    {
        return 1;
    }

    /**
     * Генерация элемента.
     *
     * @return Generator<Item> Генерируемый элемент.
     */
    public function generate(): Generator
    {
        $promocode = Promocode::applicable()
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $promotion = Promotion::active()
            ->orderBy('updated_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        $dates = [];

        if ($promocode) {
            $dates[] = Carbon::parse($promocode->updated_at);
        }

        if ($promotion) {
            $dates[] = Carbon::parse($promotion->updated_at);
        }

        $lastmod = count($dates) ? max($dates) : null;

        $item = new Item();
        $item->path = '/promos';
        $item->priority = 0.7;
        $item->lastmod = $lastmod ?: Carbon::now();

        yield $item;
    }
}
