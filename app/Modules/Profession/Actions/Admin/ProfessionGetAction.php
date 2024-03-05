<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;
use Cache;
use Util;

/**
 * Класс действия для получения профессии.
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
        $id = $this->id;
        $cacheKey = Util::getKey('profession', $id, 'metatag');

        return Cache::tags(['catalog', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                $profession = Profession::where('id', $id)
                    ->with([
                        'metatag',
                        'analyzers',
                    ])->first();

                return $profession ? ProfessionEntity::from($profession->toArray()) : null;
            }
        );
    }
}
