<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Filters;

use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы ролей пользователей.
 */
class FaqFilter extends ModelFilter
{
    /**
     * Массив сопоставлений атрибутом поиска отношений с методом его реализации.
     *
     * @var array
     */
    public $relations = [
        'school' => [
            'school-id'  => 'schoolId',
        ]
    ];

    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return FaqFilter Правила поиска.
     */
    public function id(int|string $id): self
    {
        return $this->where('faqs.id', $id);
    }

    /**
     * Поиск по школам.
     *
     * @param array|int|string $schoolIds ID's школ.
     *
     * @return FaqFilter Правила поиска.
     */
    public function schoolId(array|int|string $schoolIds): self
    {
        return $this->whereIn('school_id', is_array($schoolIds) ? $schoolIds : [$schoolIds]);
    }

    /**
     * Поиск по вопросу.
     *
     * @param string $query Строка поиска.
     *
     * @return FaqFilter Правила поиска.
     */
    public function question(string $query): self
    {
        return $this->whereLike('faqs.question', $query);
    }

    /**
     * Поиск по ответу.
     *
     * @param string $query Строка поиска.
     *
     * @return FaqFilter Правила поиска.
     */
    public function answer(string $query): self
    {
        return $this->whereLike('faqs.answer', $query);
    }

    /**
     * Поиск по статусу.
     *
     * @param bool $status Статус.
     *
     * @return FaqFilter Правила поиска.
     */
    public function status(bool $status): self
    {
        return $this->where('faqs.status', $status);
    }
}
