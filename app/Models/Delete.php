<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * Трейт для модели, помогает при удалении.
 */
trait Delete
{
    /**
     * Специализированный метод, который удаляет все модели в связи и при этом вызывает их события.
     *
     * @param  Relation  $relation  Отношения модели.
     * @param  bool  $force  Просим удалить записи полностью.
     *
     * @return object Вернет объект.
     */
    public function deleteRelation(Relation $relation, bool $force = false): object
    {
        $models = $relation->get();

        foreach ($models as $model) {
            if ($force) {
                $model->forceDelete();
            } else {
                $model->delete();
            }
        }

        return $this;
    }
}
