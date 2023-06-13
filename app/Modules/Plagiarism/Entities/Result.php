<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Entities;

/**
 * Сущность, которая хранит данные готового результата анализа.
 */
class Result
{
    /**
     * Уникальность текста.
     *
     * @var float|null
     */
    public ?float $unique = null;

    /**
     * Процент воды.
     *
     * @var int|null
     */
    public ?int $water = null;

    /**
     * Процент спама.
     *
     * @var int|null
     */
    public ?int $spam = null;
}
