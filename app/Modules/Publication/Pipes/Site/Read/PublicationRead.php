<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Enums\CacheTime;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use Cache;
use Closure;
use Util;
use View;
use Storage;
use App\Models\Entity;
use ReflectionException;
use App\Models\Contracts\Pipe;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Models\Publication;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;

/**
 * Класс пайплайн для публикации.
 */
class PublicationRead implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|PublicationReadEntity $entity Сущность для чтения публикаций.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|PublicationReadEntity $entity, Closure $next): mixed
    {
        $id = $entity->id;
        $year = $entity->year;
        $link = $entity->link;
        $limit = $entity->limit;

        $cacheKey = Util::getKey(
            'publication',
            'read',
            $year,
            $link,
            $id,
            $limit,
            'active',
            'metatag'
        );

        $publications = Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($year, $link, $id, $limit) {
                $publication = Publication::limit($limit)
                    ->year($year)
                    ->link($link)
                    ->id($id)
                    ->orderBy('published_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->with('metatag')
                    ->active()
                    ->get();

                return $publication ? Entity::toEntities($publication->toArray(), new PublicationEntity()) : null;
            }
        );

        if ($entity->id || $entity->link) {
            if (isset($publications[0])) {
                $publication = $publications[0];

                if ($publication) {
                    $pathFile = $publication->id . '.blade.php';

                    if (!Storage::disk('publications')->exists($pathFile)) {
                        Storage::disk('publications')->put($pathFile, $publication->article ?: '');
                    }

                    $publication->article = View::make('tmp.publications.' . $publication->id)
                        ->render();

                    $entity->publication = $publication;
                }
            }
        } else {
            $entity->publications = $publications;
        }

        return $next($entity);
    }
}
