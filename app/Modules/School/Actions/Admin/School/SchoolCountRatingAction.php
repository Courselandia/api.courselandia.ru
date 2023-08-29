<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Models\Event;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Review\Enums\Status as ReviewStatus;

/**
 * Класс действия для подсчета рейтинга для каждой школе.
 */
class SchoolCountRatingAction extends Action
{
    use Event;

    /**
     * Метод запуска логики.
     *
     * @return bool Успешность выполнения.
     */
    public function run(): bool
    {
        $schools = $this->getQuery()
            ->withCount([
                'reviews' => function ($query) {
                    $query->where('status', ReviewStatus::ACTIVE->value);
                },
            ])
            ->withSum(
                [
                    'reviews' => function ($query) {
                        $query->where('status', ReviewStatus::ACTIVE->value);
                    }
                ],
                'rating'
            )
            ->get();

        foreach ($schools as $school) {
            $school->rating = round($school->reviews_sum_rating / $school->reviews_count, 2);
            $school->save();

            $this->fireEvent('saved');
        }

        return true;
    }

    /**
     * Количество школ.
     *
     * @return int Вернет количество обрабатываемых школ.
     */
    public function getCountSchools(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Вернет запрос на получение школ..
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return School::where('status', true)
            ->whereHas('reviews', function ($query) {
                $query->where('status', ReviewStatus::ACTIVE->value);
            });
    }
}
