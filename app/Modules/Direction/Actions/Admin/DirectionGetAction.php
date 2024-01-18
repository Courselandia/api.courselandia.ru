<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Direction\Models\Direction;
use Cache;
use Util;

/**
 * Класс действия для получения направления.
 */
class DirectionGetAction extends Action
{
    /**
     * ID направления.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID направления.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity|null Вернет результаты исполнения.
     */
    public function run(): ?DirectionEntity
    {
        $cacheKey = Util::getKey('direction', $this->id);

        return Cache::tags(['catalog', 'category', 'direction', 'profession', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $direction = Direction::where('id', $this->id)
                    ->with([
                        'metatag',
                        'analyzers',
                    ])
                    ->first();

                return $direction ? DirectionEntity::from($direction->toArray()) : null;
            }
        );
    }
}
