<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use Closure;
use App\Models\Entity;
use App\Modules\Publication\Entities\Publication;
use App\Modules\Publication\Entities\PublicationList;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;
use App\Models\Contracts\Pipe;

/**
 * Класс пайплайн для разбора полученных данных.
 */
class PublicationData implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|PublicationReadEntity  $entity  Сущность для чтения публикаций.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return PublicationList|Publication|null Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|PublicationReadEntity $entity, Closure $next): PublicationList|Publication|null
    {
        if ($entity->id || $entity->link) {
            return $next($entity->publication);
        } else {
            $publicationList = new PublicationList();
            $publicationList->publications = $entity->publications;
            $publicationList->year = $entity->year;
            $publicationList->years = $entity->years;
            $publicationList->total = $entity->total;

            return $next($publicationList);
        }
    }
}
