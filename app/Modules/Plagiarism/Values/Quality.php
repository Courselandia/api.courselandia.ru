<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Values;

use App\Models\Value;

/**
 * Объект-значение, которая хранит данные готового результата анализа.
 */
class Quality extends Value
{
    /**
     * Уникальность текста.
     *
     * @var float
     */
    private float $unique;

    /**
     * Процент воды.
     *
     * @var int
     */
    private int $water;

    /**
     * Процент спама.
     *
     * @var int
     */
    private int $spam;

    /**
     * @param float $unique Уникальность текста.
     * @param int $water Процент воды.
     * @param int $spam Процент спама.
     */
    public function __construct(float $unique, int $water, int $spam)
    {
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;
    }

    /**
     * Получить уникальность.
     *
     * @return float Уникальность.
     */
    public function getUnique(): float
    {
        return $this->unique;
    }

    /**
     * Получить процент воды.
     *
     * @return float Процент воды.
     */
    public function getWater(): float
    {
        return $this->water;
    }

    /**
     * Получить процент спама.
     *
     * @return float Процент спама.
     */
    public function getSpam(): float
    {
        return $this->spam;
    }
}
