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
use Util;
use View;
use Storage;
use App\Models\Entity;
use ReflectionException;
use App\Models\Contracts\Pipe;
use App\Models\Enums\SortDirection;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Repositories\Publication;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;

/**
 * Класс пайплайн для публикации.
 */
class PublicationRead implements Pipe
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|PublicationReadEntity $entity, Closure $next): mixed
    {
        $query = PublicationCondition::get($entity->year, $entity->link, $entity->id)
            ->addSort('published_at', SortDirection::DESC)
            ->addSort('id')
            ->setActive(true)
            ->setLimit($entity->limit)
            ->setRelations([
                'metatag'
            ]);

        $cacheKey = Util::getKey('publication', $query);

        $publications = Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->publication->read($query);
            }
        );

        if ($entity->id || $entity->link) {
            if (count($publications)) {
                $publication = $publications[0];

                if ($publication) {
                    $pathFile = $publication->id.'.blade.php';

                    if (!Storage::disk('publications')->exists($pathFile)) {
                        Storage::disk('publications')->put($pathFile, $publication->article ?: '');
                    }

                    $publication->article = View::make('tmp.publications.'.$publication->id)
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
