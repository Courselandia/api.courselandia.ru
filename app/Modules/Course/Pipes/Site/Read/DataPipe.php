<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Data;
use App\Modules\Course\Data\Decorators\CourseRead;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Clean;

/**
 * Чтение курсов: фильтры: очистка и подготовка данных.
 */
class DataPipe implements Pipe
{
    /**
     * Массив ключей подлежащих удалению.
     *
     * @var array
     */
    private const REMOVES = [
        'uuid',
        'metatag_id',
        'name_morphy',
        'text_morphy',
        'created_at',
        'deleted_at',
        'metatag',
        'metatag_id',
        'status',
        'weight',
        'text',
        'learns',
        'employments',
        'features',
        'byte',
        'folder',
        'format',
        'cache',
        'pathCache',
        'pathSource',
        'image_logo_id',
        'image_site_id',
        'site',
        'course_id',
        'processes',
        'openedCategories',
        'openedProfessions',
        'openedSchools',
        'openedSkills',
        'openedTeachers',
        'openedTools',
        'program',
        'direction_ids',
        'profession_ids',
        'category_ids',
        'skill_ids',
        'teacher_ids',
        'tool_ids',
        'level_values',
        'has_active_school',
        'amount_courses',
    ];

    /**
     * Массив ключей подлежащих удалению если значение содержит NULL.
     *
     * @var array
     */
    private const REMOVES_IF_NULL = [
        'link',
        'rating',
        'directions',
        'professions',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
        'schools',
        'header',
        'header_template',
        'count',
    ];

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseRead $data Данные для декоратора для чтения курсов.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|CourseRead $data, Closure $next): mixed
    {
        $data->sorts = null;
        $data->filters = null;
        $data->offset = null;
        $data->limit = null;

        $description = $data->description ? clone $data->description : null;
        $data = Clean::do($data, self::REMOVES);

        /**
         * @var CourseRead $entity
         */
        $data->description = $description;

        return $next($data);
    }
}
