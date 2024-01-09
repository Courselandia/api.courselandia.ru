<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Helpers;

use function PHPUnit\Framework\isEmpty;

/**
 * Работа с агрегациями.
 */
class ElasticAggregation
{
    /**
     * Вернет готовую агрегацию.
     *
     * @param array $resultQueryAggregations Агрегация после запроса.
     * @param string $nameAggregation Название агрегации.
     *
     * @return array Готовая агрегация.
     */
    public static function getAggregations(array $resultQueryAggregations, string $nameAggregation): array
    {
        $items = [];

        if (
            isset($resultQueryAggregations['courses'][$nameAggregation][$nameAggregation][$nameAggregation]['buckets'])
            && count($resultQueryAggregations['courses'][$nameAggregation][$nameAggregation][$nameAggregation]['buckets'])
        ) {
            $buckets = $resultQueryAggregations['courses'][$nameAggregation][$nameAggregation][$nameAggregation]['buckets'];

            for ($i = 0; $i < count($buckets); $i++) {
                $source = $buckets[$i]['fields']['hits']['hits'][0]['_source'];
                $items[$i] = [
                    'id' => $source['id'],
                    'name' => $source['name'],
                    'link' => $source['link'],
                    'count' => $buckets[$i]['doc_count'],
                ];
            }
        }

        return $items;
    }
}
