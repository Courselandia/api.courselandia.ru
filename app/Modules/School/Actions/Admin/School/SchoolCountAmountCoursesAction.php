<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Modules\Course\Enums\Status;
use DB;
use App\Models\Action;
use App\Models\Event;
use App\Modules\Direction\Enums\Direction;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс действия для подсчета и сохранения количества курсов в каждой школе.
 */
class SchoolCountAmountCoursesAction extends Action
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
                'courses as all',
                'courses as direction_programming' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::PROGRAMMING->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_marketing' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::MARKETING->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_design' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::DESIGN->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_business' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::BUSINESS->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_analytics' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::ANALYTICS->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_games' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::GAMES->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                },
                'courses as direction_other' => function (Builder $query) {
                    $query->whereRaw(DB::raw("JSON_CONTAINS(direction_ids, '" . Direction::OTHER->value . "')"))
                        ->where('status', Status::ACTIVE->value);
                }
            ])
            ->get();

        foreach ($schools as $school) {
            $amountCourses = [
                'all' => $school->all,
                'direction_programming' => $school->direction_programming,
                'direction_marketing' => $school->direction_marketing,
                'direction_design' => $school->direction_design,
                'direction_business' => $school->direction_business,
                'direction_analytics' => $school->direction_analytics,
                'direction_games' => $school->direction_games,
                'direction_other' => $school->direction_other,
            ];

            $oldAmountCourses = $school->amount_courses;
            ksort($amountCourses);
            ksort($oldAmountCourses);

            if ($amountCourses !== $oldAmountCourses) {
                $school->amount_courses = $amountCourses;

                $school->save();
                exit;
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
