<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap\Parts;

use Generator;
use Carbon\Carbon;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use App\Modules\Core\Sitemap\Part;
use App\Modules\Core\Sitemap\Item;

/**
 * Генератор для общей страницы отзывов.
 */
class PartReviews extends Part
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
        $item = new Item();
        $item->path = '/reviews';
        $item->priority = 0.7;
        $item->lastmod = $this->getLastmod();

        yield $item;
    }

    /**
     * Дата последней модификации страницы.
     *
     * @return ?Carbon Дата последней модификации.
     */
    protected function getLastmod(): ?Carbon
    {
        $review = Review::where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('schools.status', true);
            })
            ->orderBy('created_at', 'DESC')
            ->first();

        return $review ? Carbon::parse($review->created_at) : null;
    }
}
