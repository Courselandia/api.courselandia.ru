<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
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
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Review::destroy($this->ids);
            Cache::tags(['catalog', 'school', 'review', 'course'])->flush();
        }

        return true;
    }
}
