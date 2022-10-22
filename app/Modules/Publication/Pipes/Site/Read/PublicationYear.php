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
use ReflectionException;
use App\Models\Contracts\Pipe;
use App\Models\Enums\CacheTime;
use App\Models\Enums\SortDirection;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Repositories\Publication;
use App\Modules\Publication\Entities\PublicationRead as PublicationReadEntity;
use App\Modules\Publication\Entities\PublicationYear as PublicationYearEntity;
use App\Modules\Publication\Repositories\RepositoryQueryBuilderPublication;

/**
 * Класс пайплайн для разбивки публикаций по годам.
 */
class PublicationYear implements Pipe
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
        if ($entity->limit) {
            $query = new RepositoryQueryBuilderPublication();

            $query->setActive(true)
                ->addSort('published_at', SortDirection::DESC)
                ->addSort('id');

            $cacheKey = Util::getKey('publication', $query);

            $publications = Cache::tags(['publication'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->publication->read($query);
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
