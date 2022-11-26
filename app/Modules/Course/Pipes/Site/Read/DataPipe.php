<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;

/**
 * Чтение курсов: фильтры: очистка и подготовка данных.
 */
class DataPipe implements Pipe
{
    /**
     * Массив ключей подлежащих удалению.
     *
     * @var array
     */
    private const REMOVES = [
        'uuid',
        'metatag_id',
        'header_morphy',
        'text_morphy',
        'created_at',
        'updated_at',
        'deleted_at',
        'metatag',
        'metatag_id',
        'status',
        'weight',
        'text',
        'learns',
        'employments',
        'features',
        'byte',
        'folder',
        'format',
        'cache',
        'pathCache',
        'pathSource'
    ];

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        unset($entity->sorts);
        unset($entity->filters);
        unset($entity->offset);
        unset($entity->limit);

        $entity = $this->clean($entity, self::REMOVES);

        return $next($entity);
    }

    /**
     * Чистка данных.
     *
     * @param Entity $entity Сущность для очистки.
     * @param array $removes Массив ключей, которые подлежат очистки.
     *
     * @return Entity Вернет очищенную сущность.
     */
    private function clean(Entity $entity, array $removes): Entity
    {
        foreach ($entity as $key => $value) {
            if (is_array($entity->$key)) {
                for ($i = 0; $i < count($entity->$key); $i++) {
                    if ($entity->$key[$i] instanceof Entity) {
                        $entity->$key[$i] = $this->clean($entity->$key[$i], $removes);
                    }
                }
            } elseif ($entity->$key instanceof Entity) {
                $entity->$key = $this->clean($entity->$key, $removes);
            } elseif (in_array($key, $removes)) {
                unset($entity->$key);
            }
        }

        return $entity;
    }
}
