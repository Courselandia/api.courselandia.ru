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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
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

                return $direction ? new DirectionEntity($direction->toArray()) : null;
            }
        );
    }
}
