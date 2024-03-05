<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Admin\Course;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Models\Course;

/**
 * Класс действия для получения курса.
 */
class CourseGetAction extends Action
{
    /**
     * ID курса.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID курса.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity|null Вернет результаты исполнения.
     */
    public function run(): ?CourseEntity
    {
        $cacheKey = Util::getKey('course', 'admin', $this->id);

        return Cache::tags(['catalog', 'course'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $course = Course::where('id', $this->id)
                    ->with([
                        'metatag',
                        'directions',
                        'professions',
                        'categories',
                        'skills',
                        'teachers',
                        'tools',
                        'processes',
                        'levels',
                        'learns',
                        'employments',
                        'features',
                        'school',
                        'analyzers',
                    ])
                    ->first();

                return $course ? CourseEntity::from($course->toArray()) : null;
            }
        );
    }
}
