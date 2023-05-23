<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Action;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Models\CourseMongoDb;

/**
 * Класс действия для прекешированных данных.
 */
class CoursePrecacheReadAction extends Action
{
    /**
     * Отступ.
     *
     * @var int
     */
    public int $offset = 0;

    /**
     * Лимит.
     *
     * @var int
     */
    public int $limit = 0;

    /**
     * Сортировка.
     *
     * @var ?array
     */
    public ?array $sorts = null;

    /**
     * Фильтры.
     *
     * @var ?array
     */
    public ?array $filters = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseRead|null Вернет результаты исполнения.
     *
     * @throws ParameterInvalidException
     */
    public function run(): ?CourseRead
    {
        if (
            $this->offset === 0
            && $this->limit === 36
            && (isset($this->sorts['name']) && mb_strtolower($this->sorts['name']) === 'asc')
        ) {
            if (empty($this->filters)) {
                $course = CourseMongoDb::where('category', 'courses')->first();

                if ($course) {
                    /**
                     * @var CourseRead $result
                     */
                    $result = unserialize($course->data);
                    $result->precache = true;

                    return $result;
                }
            } else if (count($this->filters) === 1) {
                $category = '';
                $uuid = '';

                if (isset($this->filters['directions-id'])) {
                    $category = 'directions';
                    $uuid = $this->filters['directions-id'][0];
                } else if (isset($this->filters['categories-id']) && count($this->filters['categories-id']) === 1) {
                    $category = 'categories';
                    $uuid = $this->filters['categories-id'][0];
                } else if (isset($this->filters['professions-id']) && count($this->filters['professions-id']) === 1) {
                    $category = 'professions';
                    $uuid = $this->filters['professions-id'][0];
                } else if (isset($this->filters['school-id']) && count($this->filters['school-id']) === 1) {
                    $category = 'schools';
                    $uuid = $this->filters['school-id'][0];
                } else if (isset($this->filters['skills-id']) && count($this->filters['skills-id']) === 1) {
                    $category = 'skills';
                    $uuid = $this->filters['skills-id'][0];
                } else if (isset($this->filters['teachers-id']) && count($this->filters['teachers-id']) === 1) {
                    $category = 'teachers';
                    $uuid = $this->filters['teachers-id'][0];
                } else if (isset($this->filters['tools-id']) && count($this->filters['tools-id']) === 1) {
                    $category = 'tools';
                    $uuid = $this->filters['tools-id'][0];
                }

                if ($category && $uuid) {
                    $course = CourseMongoDb::where('category', $category)
                        ->where('uuid', (int)$uuid)
                        ->first();

                    if ($course) {
                        /**
                         * @var CourseRead $result
                         */
                        $result = unserialize($course->data);
                        $result->precache = true;

                        return $result;
                    }
                }
            }
        }

        return null;
    }
}
