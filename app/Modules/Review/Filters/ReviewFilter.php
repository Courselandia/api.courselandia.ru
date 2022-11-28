<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Filters;

use App\Modules\Review\Enums\Status;
use Carbon\Carbon;
use Config;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class ReviewFilter extends ModelFilter
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
        'course' => [
            'course-Id'  => 'courseId',
        ]
    ];

    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function id(int|string $id): ReviewFilter
    {
        return $this->where('reviews.id', $id);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int|string $schoolIds ID's школ.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function schoolId(array|int|string $schoolIds): ReviewFilter
    {
        return $this->whereIn('school_id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
    }

    /**
     * Поиск по курсам.
     *
     * @param array|int|string $courseIds ID's курсов.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function courseId(array|int|string $courseIds): ReviewFilter
    {
        return $this->whereIn('course_id', is_array($courseIds) ? $courseIds : [$courseIds]);
    }

    /**
     * Поиск по втору.
     *
     * @param string $query Строка поиска.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function name(string $query): ReviewFilter
    {
        return $this->whereLike('reviews.name', $query);
    }

    /**
     * Поиск по заголовку.
     *
     * @param string $query Строка поиска.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function title(string $query): ReviewFilter
    {
        return $this->whereLike('reviews.title', $query);
    }

    /**
     * Поиск по достоинствам.
     *
     * @param string $query Строка поиска.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function advantages(string $query): ReviewFilter
    {
        return $this->whereLike('reviews.advantages', $query);
    }

    /**
     * Поиск по недостаткам.
     *
     * @param string $query Строка поиска.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function disadvantages(string $query): ReviewFilter
    {
        return $this->whereLike('reviews.disadvantages', $query);
    }

    /**
     * Поиск по рейтингу.
     *
     * @param int $rating Рейтинг.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function rating(int $rating): ReviewFilter
    {
        return $this->where('reviews.rating', $rating);
    }

    /**
     * Поиск по дате добавления.
     *
     * @param array $dates Даты от и до.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function createdAt(array $dates): ReviewFilter
    {
        $dates = [
            Carbon::createFromFormat('Y-m-d O', $dates[0])->startOfDay()->setTimezone(Config::get('app.timezone')),
            Carbon::createFromFormat('Y-m-d O', $dates[1])->endOfDay()->setTimezone(Config::get('app.timezone')),
        ];

        return $this->whereBetween('reviews.created_at', $dates);
    }

    /**
     * Поиск по статусу.
     *
     * @param Status[]|Status|string[]|string  $statuses Статусы.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function status(array|Status|string $statuses): ReviewFilter
    {
        return $this->whereIn('reviews.status', is_array($statuses) ? $statuses : [$statuses]);
    }
}
