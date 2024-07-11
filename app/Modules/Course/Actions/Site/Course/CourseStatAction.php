<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Review\Enums\Status as ReviewStatus;
use App\Modules\Course\Values\Stat;
use App\Modules\School\Models\School;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Review\Models\Review;

/**
 * Класс действия для получения курсов.
 */
class CourseStatAction extends Action
{
    /**
     * Метод запуска логики.
     *
     * @return Stat Вернет результаты исполнения.
     */
    public function run(): Stat
    {
        $cacheKey = Util::getKey('course', 'site', 'stat');

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return new Stat(
                    $this->getAmountCourses(),
                    $this->getAmountSchools(),
                    $this->getAmountTeachers(),
                    $this->getAmountReviews(),
                );
            }
        );
    }

    /**
     * Вернет количество активных школ.
     *
     * @return int Количество активных школ.
     */
    private function getAmountSchools(): int
    {
        return School::active()
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value);
            })
            ->count();
    }

    /**
     * Вернет количество активных учителей.
     *
     * @return int Количество активных учителей.
     */
    private function getAmountTeachers(): int
    {
        return Teacher::active()
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true);
            })
            ->count();
    }

    /**
     * Вернет количество активных отзывов.
     *
     * @return int Количество активных отзывов.
     */
    private function getAmountReviews(): int
    {
        return Review::where('status', ReviewStatus::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->active()
                    ->withCourses();
            })
            ->with('school')
            ->count();
    }

    /**
     * Вернет количество активных курсов.
     *
     * @return int Количество активных курсов.
     */
    private function getAmountCourses(): int
    {
        return Course::where('status', Status::ACTIVE->value)
            ->where('has_active_school', true)
            ->count();
    }
}
