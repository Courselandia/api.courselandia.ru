<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use App\Modules\Review\Data\Site\ReviewRead;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;

/**
 * Класс действия для чтения отзывов.
 */
class ReviewReadAction extends Action
{
    /**
     * Данные для чтения отзывов.
     *
     * @var ReviewRead
     */
    private ReviewRead $data;

    /**
     * @param ReviewRead $data Данные для чтения отзывов.
     */
    public function __construct(ReviewRead $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'review',
            'site',
            'read',
            'count',
            $this->data->sorts,
            $this->data->offset,
            $this->data->limit,
            $this->data->school_id,
            $this->data->link,
            $this->data->rating,
            'school',
        );

        return Cache::tags(['catalog', 'review'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Review::where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->hasCourses();

                        if ($this->data->link) {
                            $query->where('schools.link', $this->data->link);
                        }
                    })
                    ->with([
                        'school' => function ($query) {
                            $query->select([
                                'id',
                                'metatag_id',
                                'name',
                                'header',
                                'header_template',
                                'link',
                                'text',
                                'additional',
                                'rating',
                                'site',
                                'referral',
                                'status',
                                'amount_courses',
                                'amount_teachers',
                                'amount_reviews',
                                'image_logo',
                                'image_site',
                            ]);
                        }
                    ]);

                if ($this->data->school_id) {
                    $query->where('school_id', $this->data->school_id);
                }

                if ($this->data->rating) {
                    $query->where('rating', $this->data->rating);
                }

                $queryCount = $query->clone();

                $query->sorted($this->data->sorts ?: []);

                if ($this->data->offset) {
                    $query->offset($this->data->offset);
                }

                if ($this->data->limit) {
                    $query->limit($this->data->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => ReviewEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
