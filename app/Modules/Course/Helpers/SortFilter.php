<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Course\Helpers;

/**
 * Вспомогательный класс сортировки для фильтров курсов.
 */
class SortFilter
{
    /**
     *  Сортировка.
     *
     * @param array $filters Общий список фильтров.
     * @param array $currentFilters Текущие фильтры.
     * @param int|null $offset Отступ.
     * @param int|null $limit Лимит.
     *
     * @return array Вернет отсортированный список.
     */
    public static function run(array $filters, array $currentFilters, ?int $offset = 0, ?int $limit = null): array
    {
        $filters = collect($filters);

        $resultNotSelected = $filters->filter(function (array $filter) use ($currentFilters) {
            return !in_array($filter['id'], $currentFilters);
        })
        ->sortBy(function ($category) {
            return $category['name'];
        })
        ->values()
        ->toArray();

        $resultSelected = $filters->filter(function (array $filter) use ($currentFilters) {
            return in_array($filter['id'], $currentFilters);
        })
        ->sortBy(function ($category) {
            return $category['name'];
        })
        ->values()
        ->toArray();

        $result = array_merge($resultSelected, $resultNotSelected);

        return collect($result)->slice($offset ?: 0, $limit ? $limit + count($resultSelected) : null)->toArray();
    }
}
