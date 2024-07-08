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
 * Очистка для курса.
 */
class CleanCourse
{
    /**
     * Массив ключей подлежащих удалению.
     *
     * @var array
     */
    public const REMOVES = [
        'metatag_id',
        'name_morphy',
        'text_morphy',
        'created_at',
        'deleted_at',
        'status',
        'byte',
        'folder',
        'format',
        'cache',
        'pathCache',
        'pathSource',
        'image_logo_id',
        'image_site_id',
        'course_id',
        'direction_ids',
        'profession_ids',
        'category_ids',
        'skill_ids',
        'teacher_ids',
        'tool_ids',
        'level_values',
        'analyzers',
        'reviews_count',
        'reviews_1_star_count',
        'reviews_2_stars_count',
        'reviews_3_stars_count',
        'reviews_4_stars_count',
        'reviews_5_stars_count',
        'header_template',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
        'school_id',
        'title_template',
        'description_template',
        'has_active_school',
    ];

    public static function do(array $data): array
    {
        $data['course'] = Clean::do($data['course'], self::REMOVES);
        $data['course'] = self::clean($data['course']);
        $data['similarities'] = CleanCourseList::do($data['similarities']);

        unset($data['filter']);

        return $data;
    }

    /**
     * Дополнительная очистка от больших ненужных текстов.
     *
     * @param array $data Данные для очистки.
     * @return array Очищенные данные.
     */
    public static function clean(array $data): array
    {
        $params = [
            'teachers',
            'tools',
            'categories',
            'skills',
            'directions',
            'professions',
        ];

        foreach ($params as $param) {
            if (isset($data[$param])) {
                for ($z = 0; $z < count($data[$param]); $z++) {
                    unset($data[$param][$z]['text']);
                }
            }
        }

        return $data;
    }
}
