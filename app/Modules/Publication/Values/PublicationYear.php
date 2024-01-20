<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Values;

use App\Models\Value;

/**
 * Объект-значение для годов публикаций.
 */
class PublicationYear extends Value
{
    /**
     * Год.
     *
     * @var int
     */
    private int $year;

    /**
     * Статус текущего года.
     *
     * @var bool
     */
    private bool $current;

    /**
     * @param int $year Год.
     * @param bool $current Статус текущего года.
     */
    public function __construct(int $year, bool $current = false)
    {
        $this->year = $year;
        $this->current = $current;
    }

    /**
     * Получить год.
     *
     * @return int Год.
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * Получить статус текущего года.
     *
     * @return int Статус текущего года..
     */
    public function getCurrent(): int
    {
        return $this->year;
    }
}
