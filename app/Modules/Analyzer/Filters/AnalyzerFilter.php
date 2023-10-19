<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Filters;

use App\Modules\Analyzer\Enums\Status;
use EloquentFilter\ModelFilter;

/**
 * Класс фильтр для таблицы хранения анализируемых текстов для SEO.
 */
class AnalyzerFilter extends ModelFilter
{
    /**
     * Поиск по ID.
     *
     * @param int|string $id ID.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function id(int|string $id): AnalyzerFilter
    {
        return $this->where('analyzers.id', $id);
    }

    /**
     * Поиск по категории.
     *
     * @param string|array $category Категории.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function category(string|array $category): AnalyzerFilter
    {
        return $this->whereIn('analyzers.category', is_array($category) ? $category : [$category]);
    }

    /**
     * Поиск по уникальности.
     *
     * @param float[] $unique Уникальность.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function unique(array $unique): AnalyzerFilter
    {
        return $this->whereBetween('analyzers.unique', $unique);
    }

    /**
     * Поиск по проценту воды.
     *
     * @param float[] $water Процент воды.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function water(array $water): AnalyzerFilter
    {
        return $this->whereBetween('analyzers.water', $water);
    }

    /**
     * Поиск по проценту спама.
     *
     * @param float[] $spam Процент спама.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function spam(array $spam): AnalyzerFilter
    {
        return $this->whereBetween('analyzers.spam', $spam);
    }

    /**
     * Поиск по ID сущности.
     *
     * @param int|string $analyzerableId ID сущности.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function analyzerable(int|string $analyzerableId): AnalyzerFilter
    {
        return $this->where('analyzers.analyzerable_id', $analyzerableId);
    }

    /**
     * Поиск по названию сущности.
     *
     * @param string $analyzerableType Название сущности.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function analyzerableType(string $analyzerableType): AnalyzerFilter
    {
        return $this->where('analyzers.analyzerable_type', $analyzerableType);
    }

    /**
     * Поиск по статусу.
     *
     * @param array|Status|string $statuses Статусы.
     *
     * @return AnalyzerFilter Правила поиска.
     */
    public function status(array|Status|string $statuses): AnalyzerFilter
    {
        return $this->whereIn('analyzers.status', is_array($statuses) ? $statuses : [$statuses]);
    }
}
