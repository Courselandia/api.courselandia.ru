<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Actions\Admin;

use App\Models\Action;
use App\Modules\Review\Models\Review;
use Cache;

/**
 * Класс действия для удаления отзывов.
 */
class ReviewDestroyAction extends Action
{
    /**
     * Массив ID отзывов.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param int[]|string[] $ids Массив ID отзывов.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Review::destroy($this->ids);
            Cache::tags(['catalog', 'review'])->flush();
        }

        return true;
    }
}
