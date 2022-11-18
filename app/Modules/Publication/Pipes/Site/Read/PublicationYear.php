<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use Util;
use Cache;
use Closure;
use Carbon\Carbon;
use App\Models\Entity;
use App\Models\Contracts\Pipe;
use App\Models\Enums\CacheTime;
use App\Modules\Publication\Models\Publication;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;
use App\Modules\Publication\Entities\PublicationYear as PublicationYearEntity;

/**
 * Класс пайплайн для разбивки публикаций по годам.
 */
class PublicationYear implements Pipe
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
            $cacheKey = Util::getKey('publication', 'model');

            $publications = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return Publication::active()
                        ->select('published_at')
                        ->orderBy('published_at', 'desc')
                        ->orderBy('id', 'desc')
                        ->get();
                }
            );

            $years = [];

            for ($i = 0; $i < count($publications); $i++) {
                $years[] = Carbon::parse($publications[$i]->published_at)->year;
            }

            $years = array_unique($years);
            $items = [];
            $year = $entity->year ?? Carbon::now()->year;

            foreach ($years as $yr) {
                $publicationYear = new PublicationYearEntity();
                $publicationYear->year = $yr;
                $publicationYear->current = $yr === $year;

                $items[] = $publicationYear;
            }

            $entity->years = $items;
        }

        return $next($entity);
    }
}
