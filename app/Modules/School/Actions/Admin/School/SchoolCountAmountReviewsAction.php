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
use App\Modules\Review\Enums\Status as ReviewStatus;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс действия для подсчета и сохранения количества отзывов в каждой школе.
 */
class SchoolCountAmountReviewsAction extends Action
{
    use Event;

    /**
     * Метод запуска логики.
     *
     * @return bool Успешность выполнения.
     */
    public function run(): bool
    {
        $schools = School::where('status', true)
            ->withcount([
                'reviews as current_amount_reviews' => function (Builder $query) {
                    $query->where('status', ReviewStatus::ACTIVE->value);
                },
            ])
            ->get();

        foreach ($schools as $school) {
            if ($school->current_amount_reviews !== $school->amount_reviews) {
                $school->amount_reviews = $school->current_amount_reviews;

                $school->save();
            }

            $this->fireEvent('saved');
        }

        return true;
    }

    /**
     * Количество школ.
     *
     * @return int Вернет количество обрабатываемых школ.
     */
    public static function getCountSchools(): int
    {
        return School::where('status', true)->count();
    }
}
