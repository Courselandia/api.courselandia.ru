<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use Cache;
use Closure;
use Util;
use App\Models\Data;
use ReflectionException;
use App\Models\Enums\CacheTime;
use App\Models\Contracts\Pipe;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Models\Publication;
use App\Modules\Publication\Data\Decorators\PublicationRead as PublicationReadData;

/**
 * Класс пайплайн для публикации.
 */
class PublicationRead implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|PublicationReadData $data $entity Данные для чтения публикаций.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Data|PublicationReadData $data, Closure $next): mixed
    {
        $id = $data->id;
        $year = $data->year;
        $link = $data->link;
        $limit = $data->limit;
        $offset = $data->offset;

        $cacheKey = Util::getKey(
            'publication',
            'read',
            $year,
            $link,
            $id,
            $limit,
            $offset,
            'active',
            'metatag'
        );

        $publications = Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($year, $link, $id, $limit, $offset) {
                $query = Publication::limit($limit)
                    ->orderBy('published_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->with('metatag')
                    ->active();

                if ($year) {
                    $query->year($year);
                }

                if ($link) {
                    $query->link($link);
                }

                if ($id) {
                    $query->id($id);
                }

                if ($offset) {
                    $query->offset($offset);
                }

                $publications = $query->get();

                return $publications ? PublicationEntity::collection($publications) : null;
            }
        );

        if ($data->id || $data->link) {
            if (isset($publications[0])) {
                $data->publication = $publications[0];
            }
        } else {
            $data->publications = $publications;
        }

        return $next($data);
    }
}
