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
use App\Modules\Publication\Models\Publication;
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
     * ID публикации.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID публикации.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
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
        $cacheKey = Util::getKey('publication', 'model', $this->id);

        $publication = Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function ()  {
                return Publication::find($this->id);
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

            $publication->save();
            Cache::tags(['publication'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('publication::actions.admin.publicationImageDestroyAction.notExistPublication')
        );
    }
}
