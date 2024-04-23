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
use App\Modules\Course\Enums\Status;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс действия для подсчета и сохранения количества учителей в каждой школе.
 */
class SchoolCountAmountTeachersAction extends Action
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
                'teachers as current_amount_teachers' => function (Builder $query) {
                    $query->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    });
                },
            ])
            ->get();

        foreach ($schools as $school) {
            if ($school->current_amount_teachers !== $school->amount_teachers) {
                $school->amount_teachers = $school->current_amount_teachers;

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
