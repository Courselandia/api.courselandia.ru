<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Filters;

use App\Modules\Review\Enums\Status;
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
            'school-name'  => 'schoolName',
        ]
    ];

    /**
     * Поиск по ID.
     *
     * @param int $id ID.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function id(int $id): ReviewFilter
    {
        return $this->where('reviews.id', $id);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int $schoolIds ID's школ.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function schoolName(array|int $schoolIds): ReviewFilter
    {
        return $this->related('school', function($query) use ($schoolIds) {
            return $query->whereIn('schools.id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
        });
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
     * Поиск по тексту.
     *
     * @param string $query Строка поиска.
     *
     * @return ReviewFilter Правила поиска.
     */
    public function text(string $query): ReviewFilter
    {
        return $this->whereLike('reviews.text', $query);
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
