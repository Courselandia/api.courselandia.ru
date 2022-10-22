<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Repositories\Publication;
use App\Modules\Publication\Repositories\RepositoryQueryBuilderPublication;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения публикации.
 */
class PublicationGetAction extends Action
{
    /**
     * Репозиторий публикаций.
     *
     * @var Publication
     */
    private Publication $publication;

    /**
     * ID публикации.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * Метод запуска логики.
     *
     * @return PublicationEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?PublicationEntity
    {
        $query = new RepositoryQueryBuilderPublication();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('publication', $query);

        return Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->publication->get($query);
            }
        );
    }
}
