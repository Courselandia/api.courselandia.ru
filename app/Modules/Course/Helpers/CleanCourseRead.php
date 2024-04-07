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
 * Очистка для чтения курсов.
 */
class CleanCourseRead
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
        'directions',
        'professions',
        'categories',
        'skills',
        'teachers',
        'tools',
        'levels',
        'analyzers',
        'reviews_count',
        'reviews_1_star_count',
        'reviews_2_stars_count',
        'reviews_3_stars_count',
        'reviews_4_stars_count',
        'reviews_5_stars_count',
        'header_template',
        'language',
        'online',
        'employment',
        'additional'
    ];

    public static function do(array $data): array
    {
        unset($data['sorts']);
        unset($data['filters']);
        unset($data['offset']);
        unset($data['limit']);

        $description = $data['description'];
        $data = Clean::do($data, self::REMOVES);

        $data['description'] = $description;

        return $data;
    }
}
