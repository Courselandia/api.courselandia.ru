<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap\Parts;

use App\Modules\Course\Enums\Status;
use App\Modules\Review\Actions\Site\ReviewReadAction;
use App\Modules\Review\Data\Site\ReviewRead;
use App\Modules\Review\Entities\Review;
use App\Modules\Review\Enums\Status as ReviewStatus;
use App\Modules\School\Models\School;
use App\Modules\Sitemap\Sitemap\Item;
use App\Modules\Sitemap\Sitemap\Part;
use Carbon\Carbon;
use Generator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Генератор для отзывов.
 */
class PartReview extends Part
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
                $item->path = '/reviews/' . $result['link'];
                $item->priority = 0.8;
                $item->lastmod = $this->getLastmod($result['link']);

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
        return School::select([
            'schools.link',
        ])
        ->whereHas('courses', function ($query) {
            $query->select([
                'courses.id',
            ])
            ->where('status', Status::ACTIVE->value);
        })
        ->whereHas('reviews', function ($query) {
            $query->select([
                'reviews.id',
            ])
            ->where('status', ReviewStatus::ACTIVE->value);
        })
        ->where('status', true)
        ->orderBy('name');
    }

    /**
     * Дата последней модификации страницы.
     *
     * @param string $link Ссылка на школу.
     *
     * @return ?Carbon Дата последней модификации.
     */
    protected function getLastmod(string $link): ?Carbon
    {
        $data = ReviewRead::from([
            'offset' => 0,
            'limit' => 20,
            'link' => $link,
            'sorts' => [
                'created_at' => 'DESC',
            ],
        ]);
        $action = new ReviewReadAction($data);
        $result = $action->run();
        $reviews = $result['data'];
        $dates = [];

        foreach ($reviews as $review) {
            /**
             * @var Review $review
             */
            $dates[] = $review->updated_at;
            $dates[] = $review->school->updated_at;
        }

        return max($dates);
    }
}
