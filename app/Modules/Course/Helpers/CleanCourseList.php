<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Helpers;

use App\Models\Clean;

/**
 * Очистка для получения небольшого списка курсов.
 */
class CleanCourseList
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
        'filter',
        'section',
        'sectionLink',
        'description',
        'disabled',
        'program',
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
        'header_template',
        'header',
        'count',
    ];

    public static function do(array $data): array
    {
        unset($data['sorts']);
        unset($data['filters']);
        unset($data['offset']);
        unset($data['limit']);
        unset($data['openedSchools']);
        unset($data['openedCategories']);
        unset($data['openedProfessions']);
        unset($data['openedTeachers']);
        unset($data['openedSkills']);
        unset($data['openedTools']);

        $data = Clean::do($data, self::REMOVES);
        $data = Clean::do($data, self::REMOVES_IF_NULL, true);

        unset($data['filter']);

        return $data;
    }
}
