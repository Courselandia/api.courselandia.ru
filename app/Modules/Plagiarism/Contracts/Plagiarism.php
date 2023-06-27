<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Contracts;

use App\Modules\Plagiarism\Entities\Result;

/**
 * Абстрактный класс для проведения анализа текста.
 */
abstract class Plagiarism
{
    /**
     * Запрос на проведения анализа.
     *
     * @param string $text Текст для проведения анализа.
     * @return string ID задачи на анализ.
     */
    abstract public function request(string $text): string;

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     * @return Result Готовый анализ.
     */
    abstract public function result(string $id): Result;
}
