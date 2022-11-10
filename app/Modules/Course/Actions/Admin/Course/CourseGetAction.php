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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Repositories\Course;

/**
 * Класс действия для получения курса.
 */
class CourseGetAction extends Action
{
    /**
     * Репозиторий курсов.
     *
     * @var Course
     */
    private Course $course;

    /**
     * ID курса.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param Course $course Репозиторий курсов.
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?CourseEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
                'directions',
                'professions',
                'categories',
                'skills',
                'teachers',
                'tools',
                'levels',
                'learns',
                'employments',
                'features',
            ]);

        $cacheKey = Util::getKey('course', $query);

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->course->get($query);
            }
        );
    }
}
