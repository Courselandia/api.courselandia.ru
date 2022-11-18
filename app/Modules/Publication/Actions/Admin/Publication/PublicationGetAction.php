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
use App\Modules\Publication\Models\Publication;
use Cache;
use Util;

/**
 * Класс действия для получения публикации.
 */
class PublicationGetAction extends Action
{
    /**
     * ID публикации.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?PublicationEntity
    {
        $cacheKey = Util::getKey('publication', $this->id, 'metatag');

        return Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $publication = Publication::where('id', $this->id)
                    ->with('metatag')
                    ->first();

                return $publication ? new PublicationEntity($publication->toArray()) : null;
            }
        );
    }
}
