<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use App\Modules\Sitemap\Sitemap\Item;
use App\Modules\Sitemap\Sitemap\Part;
use Carbon\Carbon;
use Generator;

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
                $query->active()
                    ->hasCourses();
            })
            ->orderBy('created_at', 'DESC')
            ->first();

        return $review ? Carbon::parse($review->created_at) : null;
    }
}
