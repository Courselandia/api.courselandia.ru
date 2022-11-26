<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use Util;

/**
 * Класс действия для чтения отзывов.
 */
class ReviewReadAction extends Action
{
    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * ID школа.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * Конструктор.
     *
     * @param  Review  $review  Репозиторий отзывов.
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $cacheKey = Util::getKey(
            'review',
            'site',
            'read',
            'count',
            $this->sorts,
            $this->offset,
            $this->limit,
            'school',
        );

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Review::where('school_id', $this->school_id)
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->sorted($this->sorts ?: [])
                    ->with('school');

                $queryCount = $query->clone();

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new ReviewEntity()),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
