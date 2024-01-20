<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Pipes\Site\Read;

use App\Models\Data;
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
                $publications = Publication::limit($limit)
                    ->year($year)
                    ->link($link)
                    ->id($id)
                    ->orderBy('published_at', 'DESC')
                    ->orderBy('id', 'DESC')
                    ->with('metatag')
                    ->active()
                    ->get();

                return $publications ? PublicationEntity::collection($publications) : null;
            }
        );

        if ($data->id || $data->link) {
            if (isset($publications[0])) {
                $publication = $publications[0];

                if ($publication) {
                    $pathFile = $publication->id . '.blade.php';

                    if (!Storage::disk('publications')->exists($pathFile)) {
                        Storage::disk('publications')->put($pathFile, $publication->article ?: '');
                    }

                    $publication->article = View::make('tmp.publications.' . $publication->id)
                        ->render();

                    $data->publication = $publication;
                }
            }
        } else {
            $data->publications = $publications;
        }

        return $next($data);
    }
}
