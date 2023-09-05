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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Core\Sitemap\Part;
use App\Modules\Review\Actions\Site\ReviewReadAction;
use App\Modules\Review\Entities\Review;
use App\Modules\Core\Sitemap\Item;
use App\Modules\Course\Enums\Status;
use App\Modules\Review\Enums\Status as ReviewStatus;
use App\Modules\School\Models\School;
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
     * @throws ParameterInvalidException
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
     * @throws ParameterInvalidException
     */
    protected function getLastmod(string $link): ?Carbon
    {
        $action = app(ReviewReadAction::class);
        $action->offset = 0;
        $action->limit = 20;
        $action->link = $link;
        $action->sorts = [
            'created_at' => 'DESC',
        ];

        $dates = [];
        $result = $action->run();
        $reviews = $result['data'];

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
