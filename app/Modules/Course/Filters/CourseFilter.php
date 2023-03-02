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
            'school-id'  => 'schoolId',
        ],
        'directions' => [
            'directions-id'  => 'directionsId',
        ],
        'professions' => [
            'professions-id'  => 'professionsId',
        ],
        'categories' => [
            'categories-id'  => 'categoriesId',
        ],
        'skills' => [
            'skills-id'  => 'skillsId',
        ],
        'teachers' => [
            'teachers-id'  => 'teachersId',
        ],
        'tools' => [
            'tools-id'  => 'toolsId',
        ],
        'levels' => [
            'levels-level'  => 'levelsLevel',
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
        $queryMorph = Morph::get($query);

        return $this->where(function ($q) use ($queryMorph, $query) {
            return $q
                ->whereRaw(
                    'MATCH(header_morphy, text_morphy) AGAINST(? IN BOOLEAN MODE)',
                    [$queryMorph]
                );
            }
        );
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
     * Поиск по наличию рассрочки.
     *
     * @param bool $status Признак наличия или отсутствия рассрочки.
     *
     * @return CourseFilter Правила поиска.
     */
    public function credit(bool $status): CourseFilter
    {
        if ($status) {
            return $this->whereNotNull('courses.price_recurrent');
        } else {
            return $this->where(function($query) {
                return $query
                    ->whereNull('courses.price_recurrent')
                    ->orWhere('courses.price_recurrent', '=', 0);
            });
        }
    }

    /**
     * Поиск бесплатных курсов.
     *
     * @param bool $status Признак наличия или отсутствия бесплатных курсов.
     *
     * @return CourseFilter Правила поиска.
     */
    public function free(bool $status): CourseFilter
    {
        if ($status) {
            return $this->where(function($query) {
                return $query
                    ->whereNull('courses.price')
                    ->orWhere('courses.price', '=', 0);
            });
        } else {
            return $this->whereNotNull('courses.price');
        }
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
        $online = $online ? 1 : 0;
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
        if ($duration[0] == 0 && $duration[1] == 0) {
            return $this
                ->where('courses.duration_rate', '>=', '0')
                ->where('courses.duration_rate', '<', '1');
        } else {
            return $this->whereBetween('courses.duration_rate', $duration);
        }
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
     * Поиск по направлениям.
     *
     * @param array|int|string $ids ID's направлений.
     *
     * @return CourseFilter Правила поиска.
     */
    public function directionsId(array|int|string $ids): CourseFilter
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
    public function professionsId(array|int|string $ids): CourseFilter
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
    public function categoriesId(array|int|string $ids): CourseFilter
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
    public function skillsId(array|int|string $ids): CourseFilter
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
    public function teachersId(array|int|string $ids): CourseFilter
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
    public function toolsId(array|int|string $ids): CourseFilter
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
    public function levelsLevel(array|Level|string $levels): CourseFilter
    {
        return $this->related('levels', function($query) use ($levels) {
            return $query->whereIn('course_levels.level', is_array($levels) ? $levels : [$levels]);
        });
    }
}
