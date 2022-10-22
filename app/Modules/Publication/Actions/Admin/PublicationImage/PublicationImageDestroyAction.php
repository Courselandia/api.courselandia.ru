<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\PublicationImage;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Publication\Repositories\Publication;
use App\Modules\Publication\Repositories\RepositoryQueryBuilderPublication;
use Cache;
use ImageStore;
use ReflectionException;
use Util;

/**
 * Класс действия для удаления изображения публикации.
 */
class PublicationImageDestroyAction extends Action
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
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilderPublication($this->id);
        $cacheKey = Util::getKey('publication', $query);

        $publication = Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->publication->get($query);
            }
        );

        if ($publication) {
            if ($publication->image_small_id) {
                ImageStore::destroy($publication->image_small_id->id);
            }

            if ($publication->image_middle_id) {
                ImageStore::destroy($publication->image_middle_id->id);
            }

            if ($publication->image_big_id) {
                ImageStore::destroy($publication->image_big_id->id);
            }

            $publication->image_small_id = null;
            $publication->image_middle_id = null;
            $publication->image_big_id = null;

            $this->publication->update($this->id, $publication);
            Cache::tags(['publication'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('publication::actions.admin.publicationImageDestroyAction.notExistPublication')
        );
    }
}
