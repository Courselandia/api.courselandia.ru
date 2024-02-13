<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;

/**
 * Класс действия для получения категории.
 */
class DirectionGetAction extends Action
{
    /**
     * ID категории.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID категории.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?DirectionEntity
    {
        $cacheKey = Util::getKey('direction', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Direction::with([
                    'metatag',
                ])
                    ->active()
                    ->find($this->id);

                return $result ? DirectionEntity::from($result->toArray()) : null;
            }
        );
    }
}
