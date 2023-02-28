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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения курса.
 */
class CourseGetAction extends Action
{
    /**
     * Ссылка школы.
     *
     * @var string|null
     */
    public string|null $school = null;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    public string|null $course = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?CourseEntity
    {
        $cacheKey = Util::getKey('course', 'site', $this->school, $this->course);

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'process',
            'employment',
            'review',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $course = Course::where('link', $this->course)
                    ->with([
                        'metatag',
                        'professions' => function ($query) {
                            $query->where('status', true);
                        },
                        'professions.salaries' => function ($query) {
                            $query->where('status', true);
                        },
                        'categories' => function ($query) {
                            $query->where('status', true);
                        },
                        'skills' => function ($query) {
                            $query->where('status', true);
                        },
                        'teachers' => function ($query) {
                            $query->where('status', true);
                        },
                        'tools' => function ($query) {
                            $query->where('status', true);
                        },
                        'processes' => function ($query) {
                            $query->where('status', true);
                        },
                        'school' => function ($query) {
                            $query->where('status', true);
                        },
                        'school.faqs' => function ($query) {
                            $query->where('status', true);
                        },
                        'directions' => function ($query) {
                            $query->where('status', true);
                        },
                        'levels',
                        'learns',
                        'employments',
                        'features',
                        'professions.salaries'
                    ])
                    ->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true)
                            ->where('link', $this->school);
                    })
                    ->first();

                return $course ? new CourseEntity($course->toArray()) : null;
            }
        );
    }
}
