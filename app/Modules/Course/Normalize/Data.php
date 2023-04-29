<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Normalize;

use App\Modules\Direction\Models\Direction;
use App\Modules\Profession\Models\Profession;
use App\Modules\Salary\Enums\Level;
use App\Modules\School\Models\School;
use App\Modules\Skill\Models\Skill;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Tool\Models\Tool;

/**
 * Получения нормализованных данных каталога.
 */
class Data
{
    /**
     * Получение активных направлений.
     *
     * @param array $ids Массив ID направлений.
     *
     * @return array Вернет массив ID только активных направлений.
     */
    public static function getDirections(array $ids): array
    {
        return Direction::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Получение активных профессий.
     *
     * @param array $ids Массив ID профессий.
     *
     * @return array Вернет массив ID только активных профессий.
     */
    public static function getProfessions(array $ids): array
    {
        return Profession::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Получение активных категорий.
     *
     * @param array $ids Массив ID категорий.
     *
     * @return array Вернет массив ID только активных категорий.
     */
    public static function getCategories(array $ids): array
    {
        return Profession::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Получение активных навыков.
     *
     * @param array $ids Массив ID навыков.
     *
     * @return array Вернет массив ID только активных навыков.
     */
    public static function getSkills(array $ids): array
    {
        return Skill::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Получение активных учителей.
     *
     * @param array $ids Массив ID учителей.
     *
     * @return array Вернет массив ID только активных учителей.
     */
    public static function getTeachers(array $ids): array
    {
        return Teacher::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Получение активных инструментов.
     *
     * @param array $ids Массив ID инструментов.
     *
     * @return array Вернет массив ID только активных инструментов.
     */
    public static function getTools(array $ids): array
    {
        return Tool::whereIn('id', $ids)
            ->where('status', true)
            ->get()
            ->pluck('id')
            ->toArray();
    }

    /**
     * Вернет признак того, что школа активна.
     *
     * @param int $id ID школы.
     *
     * @return bool Признак активности.
     */
    public static function isActiveSchool(int $id): bool
    {
        return School::where('id', $id)
            ->where('status', true)
            ->exists();
    }

    /**
     * Переведет нормализованное значение уровни.
     *
     * @param array|Level|string $levels Массив уровней в не нормализованном виде.
     *
     * @return array Массив уровней в нормализованном виде.
     */
    public static function getLevels(array|Level|string $levels): array
    {
        $levels = is_array($levels) ? $levels : [$levels];

        for ($i = 0; $i < count($levels); $i++) {
            if ($levels[$i] instanceof Level) {
                $levels[$i] = $levels[$i]->value;
            }

            if ($levels[$i] === Level::JUNIOR->value) {
                $levels[$i] = 1;
            } else if ($levels[$i] === Level::MIDDLE->value) {
                $levels[$i] = 2;
            } else if ($levels[$i] === Level::SENIOR->value) {
                $levels[$i] = 3;
            }
        }

        return $levels;
    }
}
