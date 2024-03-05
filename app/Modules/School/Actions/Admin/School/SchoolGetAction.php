<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Cache;
use Util;

/**
 * Класс действия для получения школы.
 */
class SchoolGetAction extends Action
{
    /**
     * ID школы.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID школы.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SchoolEntity
    {
        $cacheKey = Util::getKey('school', $this->id, 'metatag');

        return Cache::tags(['catalog', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $school = School::where('id', $this->id)
                    ->with([
                        'metatag',
                        'analyzers',
                    ])
                    ->first();

                return $school ? SchoolEntity::from($school->toArray()) : null;
            }
        );
    }
}
