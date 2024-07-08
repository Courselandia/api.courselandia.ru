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
     * Лимит длины текста для описания курса.
     */
    public const LIMIT_TEXT = 300;

    /**
     * Массив ключей подлежащих удалению.
     *
     * @var array
     */
    public const REMOVES = [
        'uuid',
        'metatag_id',
        'name_morphy',
        'text_morphy',
        'created_at',
        'deleted_at',
        'metatag',
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
        'additional',
        'image_small_id',
        'image_middle_id',
        'image_big_id',
    ];

    public static function do(array $data): array
    {
        unset($data['sorts']);
        unset($data['filters']);
        unset($data['offset']);
        unset($data['limit']);

        $description = $data['description'];
        $data = Clean::do($data, self::REMOVES);
        $data = self::clean($data);
        $data['description'] = $description;

        return $data;
    }

    /**
     * Дополнительная очистка от больших ненужных текстов.
     *
     * @param array $data Данные для очистки.
     * @return array Очищенные данные.
     */
    private static function clean(array $data): array
    {
        for ($i = 0; $i < count($data['courses']); $i++) {
            if (isset($data['courses'][$i]['teachers'])) {
                for ($z = 0; $z < count($data['courses'][$i]['teachers']); $z++) {
                    unset($data['courses'][$i]['teachers'][$z]['text']);
                }
            }

            if (isset($data['courses'][$i]['tools'])) {
                for ($z = 0; $z < count($data['courses'][$i]['tools']); $z++) {
                    unset($data['courses'][$i]['tools'][$z]['text']);
                }
            }

            if (isset($data['courses'][$i]['program'])) {
                $data['courses'][$i]['program'] = self::cleanProgram($data['courses'][$i]['program']);
            }

            if (isset($data['courses'][$i]['text'])) {
                $data['courses'][$i]['text'] = strip_tags($data['courses'][$i]['text']);

                if (strlen($data['courses'][$i]['text']) > CleanCourseRead::LIMIT_TEXT) {
                    $data['courses'][$i]['text'] = mb_substr($data['courses'][$i]['text'], 0, CleanCourseRead::LIMIT_TEXT);
                }
            }
        }

        return $data;
    }

    /**
     * Дополнительная очистка для программ от ненужных текстов.
     *
     * @param array $program Данные для очистки.
     * @return array Очищенные данные.
     */
    private static function cleanProgram(array $program): array
    {
        for ($i = 0; $i < count($program); $i++) {
            unset($program[$i]['text']);

            if (isset($program[$i]['children'])) {
                $program[$i]['children'] = self::cleanProgram($program[$i]['children']);
            }
        }

        return $program;
    }
}
