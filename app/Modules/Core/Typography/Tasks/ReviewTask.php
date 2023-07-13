<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Review\Models\Review;

/**
 * Типографирование отзывов.
 */
class ReviewTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $reviews = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($reviews as $review) {
            $review->title = Typography::process($review->title, true);
            $review->review = Typography::process($review->review, true);
            $review->advantages = Typography::process($review->advantages, true);
            $review->disadvantages = Typography::process($review->disadvantages, true);

            $review->save();

            $this->fireEvent('finished', [$review]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Review::orderBy('id', 'ASC');
    }
}
