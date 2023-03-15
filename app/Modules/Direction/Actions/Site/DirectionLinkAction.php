<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Actions\Site;

use App\Modules\Category\Http\Controllers\Site\CategoryController;
use App\Modules\Course\Enums\Status;
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
class DirectionLinkAction extends Action
{
    /**
     * ID категории.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?DirectionEntity
    {
        $cacheKey = Util::getKey('direction', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Direction::where('link', $this->link)
                    ->with([
                        'metatag',
                        'categories',
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value);
                    })
                    ->first();

                if ($result) {
                    $item = $result->toArray();
                    $entity = new DirectionEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
