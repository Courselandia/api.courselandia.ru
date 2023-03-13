<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;
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
        'header_morphy',
        'text_morphy',
        'created_at',
        'updated_at',
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
        'count',
    ];

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        unset($entity->sorts);
        unset($entity->filters);
        unset($entity->offset);
        unset($entity->limit);

        $entity = Clean::do($entity, self::REMOVES);
        $entity = Clean::do($entity, self::REMOVES_IF_NULL, true);

        return $next($entity);
    }
}
