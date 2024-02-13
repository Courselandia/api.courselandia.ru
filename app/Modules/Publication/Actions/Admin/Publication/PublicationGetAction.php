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
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity|null Вернет результаты исполнения.
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

                return $publication ? PublicationEntity::from($publication) : null;
            }
        );
    }
}
