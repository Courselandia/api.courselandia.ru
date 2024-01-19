<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;

/**
 * Класс действия для получения категории.
 */
class ProfessionGetAction extends Action
{
    /**
     * ID профессии.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID профессии.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity|null Вернет результаты исполнения.
     */
    public function run(): ?ProfessionEntity
    {
        $cacheKey = Util::getKey('profession', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Profession::with([
                    'metatag',
                ])->find($this->id);

                if ($result) {
                    return ProfessionEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
