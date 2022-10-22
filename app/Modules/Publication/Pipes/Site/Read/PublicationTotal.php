<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Enums\CacheTime;
use Cache;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;
use App\Modules\Publication\Repositories\Publication;
use Util;

/**
 * Декоратор пайплан пагинации для публикаций.
 */
class PublicationTotal implements Pipe
{
    /**
     * Репозиторий публикаций.
     *
     * @var Publication
     */
    private Publication $publication;

    /**
     * Конструктор.
     *
     * @param  Publication  $publication  Репозиторий публикаций.
     */
    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|PublicationReadEntity  $entity  Сущность для чтения публикаций.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|PublicationReadEntity $entity, Closure $next): mixed
    {
        if ($entity->limit) {
            $query = PublicationCondition::get($entity->year)
                ->setActive(true);

            $cacheKey = Util::getKey('publication', 'count', $query);

            $entity->total = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->publication->count($query);
                }
            );
        }

        return $next($entity);
    }
}
