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
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения категории.
 */
class DirectionLinkAction extends Action
{
    /**
     * Ссылка на категорию.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка на категорию.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return DirectionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?DirectionEntity
    {
        $cacheKey = Util::getKey('direction', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'direction'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Direction::where('link', $this->link)
                    ->with([
                        'metatag',
                        'categories' => function ($query) {
                            $query->where('status', true)
                                ->whereHas('courses', function ($query) {
                                    $query->where('status', Status::ACTIVE->value)
                                        ->where('has_active_school', true);
                                });
                        },
                    ])
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->first();

                if ($result) {
                    return DirectionEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
