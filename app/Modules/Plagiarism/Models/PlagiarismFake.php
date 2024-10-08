<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Models;

use App\Modules\Plagiarism\Contracts\Plagiarism;
use App\Modules\Plagiarism\Values\Quality;

/**
 * Классы драйвер для анализа текстов - фейковый драйвер, созданный для тестирования.
 * Внимание, данный драйвер имитирует анализ текстов, используйте его только для тестирования системы.
 */
class PlagiarismFake extends Plagiarism
{
    /**
     * Запрос на написание текста.
     *
     * @param string $text Текст для проведения анализа.
     *
     * @return string ID задачи на генерацию.
     */
    public function request(string $text): string
    {
        return '10';
    }

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     *
     * @return Quality Результат анализа.
     */
    public function result(string $id): Quality
    {
        return new Quality(60.50, 30, 20);
    }
}
