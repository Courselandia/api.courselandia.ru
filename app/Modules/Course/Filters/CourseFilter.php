<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Filters;

use Morph;
use App\Modules\Course\Enums\Status;
use App\Modules\Salary\Enums\Level;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для курсов.
 */
class CourseFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'school' => [
            'school-name'  => 'schoolName',
        ],
        'directions' => [
            'directions-name'  => 'directionsName',
        ],
        'professions' => [
            'professions-name'  => 'professionsName',
        ],
        'categories' => [
            'categories-name'  => 'categoriesName',
        ],
        'skills' => [
            'skills-name'  => 'skillsName',
        ],
        'teachers' => [
            'teachers-name'  => 'teachersName',
        ],
        'tools' => [
            'tools-name'  => 'toolsName',
        ],
        'levels' => [
            'levels-name'  => 'levelsName',
        ],
    ];

    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return CourseFilter Правила поиска.
     */
    public function id(int|string $id): CourseFilter
    {
        return $this->where('courses.id', $id);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int|string $schoolIds ID's школ.
     *
     * @return CourseFilter Правила поиска.
     */
    public function schoolId(array|int|string $schoolIds): CourseFilter
    {
        return $this->whereIn('courses.school_id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
    }

    /**
     * Поиск по заголовку.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseFilter Правила поиска.
     */
    public function header(string $query): CourseFilter
    {
        $queryMorph = Morph::get($query) ?? $query;

        return $this->where(function ($q) use ($queryMorph, $query) {
            return $q->whereRaw(
                'MATCH(header_morphy) AGAINST(? IN BOOLEAN MODE)',
                [$queryMorph]
            )->orWhere('header_morphy', 'LIKE', '%' . $query . '%');
        });
    }

    /**
     * Поиск по описанию.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseFilter Правила поиска.
     */
    public function text(string $query): CourseFilter
    {
        $queryMorph = Morph::get($query) ?? $query;

        return $this->where(function ($q) use ($queryMorph, $query) {
            return $q->whereRaw(
                'MATCH(text_morphy) AGAINST(? IN BOOLEAN MODE)',
                [$queryMorph]
            )->orWhere('text_morphy', 'LIKE', '%' . $query . '%');
        });
    }

    /**
     * Полнотекстовый поиск.
     *
     * @param string $query Строка поиска.
     *
     * @return CourseFilter Правила поиска.
     */
    public function search(string $query): CourseFilter
    {
        $queryMorph = Morph::get($query) ?? $query;

        return $this->where(function ($q) use ($queryMorph, $query) {
            return $q->whereRaw(
                'MATCH(header_morphy, text_morphy) AGAINST(? IN BOOLEAN MODE)',
                [$queryMorph]
            )
                ->orWhere('header_morphy', 'LIKE', '%' . $query . '%')
                ->orWhere('text_morphy', 'LIKE', '%' . $query . '%');
        });
    }

    /**
     * Поиск по рейтингу.
     *
     * @param float $rating ID.
     *
     * @return CourseFilter Правила поиска.
     */
    public function rating(float $rating): CourseFilter
    {
        return $this->where('courses.rating', '>=', $rating);
    }

    /**
     * Поиск по цене.
     *
     * @param float[] $price Цена от и до.
     *
     * @return CourseFilter Правила поиска.
     */
    public function price(array $price): CourseFilter
    {
        return $this->whereBetween('courses.price', $price);
    }

    /**
     * Поиск по статусу является ли курс онлайн курсом.
     *
     * @param bool $online Статус.
     *
     * @return CourseFilter Правила поиска.
     */
    public function online(bool $online): CourseFilter
    {
        return $this->where('courses.online', $online);
    }

    /**
     * Поиск по статусу предоставляется ли помощь в трудоустройстве после курса.
     *
     * @param bool $employment Статус.
     *
     * @return CourseFilter Правила поиска.
     */
    public function employment(bool $employment): CourseFilter
    {
        return $this->where('courses.employment', $employment);
    }

    /**
     * Поиск по продолжительности.
     *
     * @param float[] $duration Продолжительность от и до.
     *
     * @return CourseFilter Правила поиска.
     */
    public function duration(array $duration): CourseFilter
    {
        return $this->whereBetween('courses.duration_rate', $duration);
    }

    /**
     * Поиск по статусу.
     *
     * @param Status[]|Status|string[]|string  $statuses Статусы.
     *
     * @return CourseFilter Правила поиска.
     */
    public function status(array|Status|string $statuses): CourseFilter
    {
        return $this->whereIn('courses.status', is_array($statuses) ? $statuses : [$statuses]);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int|string $schoolIds ID's школ.
     *
     * @return CourseFilter Правила поиска.
     */
    public function schoolName(array|int|string $schoolIds): CourseFilter
    {
        return $this->related('school', function($query) use ($schoolIds) {
            return $query->whereIn('schools.id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
        });
    }

    /**
     * Поиск по направлениям.
     *
     * @param array|int|string $ids ID's направлений.
     *
     * @return CourseFilter Правила поиска.
     */
    public function directionsName(array|int|string $ids): CourseFilter
    {
        return $this->related('directions', function($query) use ($ids) {
            return $query->whereIn('directions.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по профессиям.
     *
     * @param array|int|string $ids ID's профессий.
     *
     * @return CourseFilter Правила поиска.
     */
    public function professionsName(array|int|string $ids): CourseFilter
    {
        return $this->related('professions', function($query) use ($ids) {
            return $query->whereIn('professions.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по категориям.
     *
     * @param array|int|string $ids ID's категории.
     *
     * @return CourseFilter Правила поиска.
     */
    public function categoriesName(array|int|string $ids): CourseFilter
    {
        return $this->related('categories', function($query) use ($ids) {
            return $query->whereIn('categories.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по навыкам.
     *
     * @param array|int|string $ids ID's навыков.
     *
     * @return CourseFilter Правила поиска.
     */
    public function skillsName(array|int|string $ids): CourseFilter
    {
        return $this->related('skills', function($query) use ($ids) {
            return $query->whereIn('skills.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по навыкам.
     *
     * @param array|int|string $ids ID's навыков.
     *
     * @return CourseFilter Правила поиска.
     */
    public function teachersName(array|int|string $ids): CourseFilter
    {
        return $this->related('teachers', function($query) use ($ids) {
            return $query->whereIn('teachers.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по инструментам.
     *
     * @param array|int|string $ids ID's инструментов.
     *
     * @return CourseFilter Правила поиска.
     */
    public function toolsName(array|int|string $ids): CourseFilter
    {
        return $this->related('tools', function($query) use ($ids) {
            return $query->whereIn('tools.id', is_array($ids) ? $ids : [$ids]);
        });
    }

    /**
     * Поиск по уровням.
     *
     * @param array|Level|string $levels Уровни.
     *
     * @return CourseFilter Правила поиска.
     */
    public function levelsName(array|Level|string $levels): CourseFilter
    {
        return $this->related('levels', function($query) use ($levels) {
            return $query->whereIn('course_levels.id', is_array($levels) ? $levels : [$levels]);
        });
    }
}
