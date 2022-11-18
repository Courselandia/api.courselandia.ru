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
use App\Modules\Publication\Models\Publication;
use Util;

/**
 * Декоратор пайплан пагинации для публикаций.
 */
class PublicationTotal implements Pipe
{
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
            $year = $entity->year;
            $cacheKey = Util::getKey('publication', 'count', $year, 'active');

            $entity->total = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($year) {
                    return Publication::year($year)->active()->count();
                }
            );
        }

        return $next($entity);
    }
}
