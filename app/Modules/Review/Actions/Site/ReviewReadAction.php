<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use JetBrains\PhpStorm\ArrayShape;

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
     * Ссылка на школу.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Рейтинг для фильтрации.
     *
     * @var int|null
     */
    public ?int $rating = null;

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
            $this->school_id,
            $this->link,
            $this->rating,
            'school',
        );

        return Cache::tags(['catalog', 'school', 'review', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Review::where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);

                        if ($this->link) {
                            $query->where('schools.link', $this->link);
                        }
                    })
                    ->with('school');

                if ($this->school_id) {
                    $query->where('school_id', $this->school_id);
                }

                if ($this->rating) {
                    $query->where('rating', $this->rating);
                }

                $queryCount = $query->clone();

                $query->sorted($this->sorts ?: []);

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
