<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Фасад класса для написания текстов искусственным интеллектом.
 */
class AnalyzerCategory extends Facade
{
    /**
     * Получить зарегистрированное имя компонента.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'analyzerCategory';
    }
}
