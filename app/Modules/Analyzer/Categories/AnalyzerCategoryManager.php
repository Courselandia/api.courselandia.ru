<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Categories;

use Illuminate\Support\Manager;

/**
 * Класс менеджер для анализа текста на основе категорий.
 */
class AnalyzerCategoryManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return 'course.text';
    }
}
