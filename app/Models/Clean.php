<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Очистка сущностей от лишних данных.
 */
class Clean
{
    /**
     * Чистка данных.
     *
     * @param Entity|Entity[] $entities Сущность для очистки.
     * @param array $removes Массив ключей, которые подлежат очистки.
     * @param bool $ifNull Только удалять если равен null.
     *
     * @return Entity|Entity[] Вернет очищенную сущность.
     */
    public static function do(Entity|array $entities, array $removes, bool $ifNull = false): Entity | array
    {
        $isArray = is_array($entities);
        $entities = $isArray ? $entities : [$entities];

        foreach ($entities as $entity) {
            foreach ($entity as $key => $value) {
                if (is_array($entity->$key)) {
                    for ($i = 0; $i < count($entity->$key); $i++) {
                        if ($entity->$key[$i] instanceof Entity) {
                            $entity->$key[$i] = self::do($entity->$key[$i], $removes, $ifNull);
                        }
                    }
                } elseif ($entity->$key instanceof Entity) {
                    $entity->$key = self::do($entity->$key, $removes, $ifNull);
                } elseif (in_array($key, $removes)) {
                    if ($ifNull === false) {
                        unset($entity->$key);
                    } else if ($ifNull === true && $entity->$key === null) {
                        unset($entity->$key);
                    }
                }
            }
        }

        return $isArray ? $entities : $entities[0];
    }
}
