<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Тип отношений принадлежит к одному.
 */
class BelongsToOne extends BelongsToMany
{
    /**
     * Инициализация отношения.
     *
     * @array array $models Массив моделей.
     *
     * @param  string  $relation  Отношение.
     *
     * @return array Вернет массив моделей.
     */
    public function initRelation(array $models, $relation): array
    {
        foreach ($models as $model) {
            $model->setRelation($relation, null);
        }

        return $models;
    }

    /**
     * Получит совпадения.
     *
     * @array array $models Массив моделей.
     * @array Collection $results Результаты совпадений.
     *
     * @param  string  $relation  Отношение.
     *
     * @return array Вернет массив моделей.
     */
    public function match(array $models, Collection $results, $relation): array
    {
        $dictionary = $this->buildDictionary($results);

        foreach ($models as $model) {
            if (isset($dictionary[$key = $model->getKey()])) {
                $model->setRelation($relation, reset($dictionary[$key]) ?: null);
            }
        }

        return $models;
    }
}
