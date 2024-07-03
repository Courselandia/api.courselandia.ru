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

        $data = Clean::do($data, CleanCourseRead::REMOVES);
        $data = self::clean($data);

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
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i]->toArray();

            if (isset($data[$i]['teachers'])) {
                for ($z = 0; $z < count($data[$i]['teachers']); $z++) {
                    unset($data[$i]['teachers'][$z]['text']);
                }
            }

            if (isset($data[$i]['tools'])) {
                for ($z = 0; $z < count($data[$i]['tools']); $z++) {
                    unset($data[$i]['tools'][$z]['text']);
                }
            }

            if (isset($data[$i]['program'])) {
                $data[$i]['program'] = self::cleanProgram($data[$i]['program']);
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
