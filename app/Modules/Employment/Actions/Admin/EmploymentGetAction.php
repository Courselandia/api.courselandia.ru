<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;
use Cache;
use Util;

/**
 * Класс действия для получения трудоустройства.
 */
class EmploymentGetAction extends Action
{
    /**
     * ID трудоустройства.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return EmploymentEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?EmploymentEntity
    {
        $cacheKey = Util::getKey('employment', $this->id, 'metatag');

        return Cache::tags(['catalog', 'employment'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $employment = Employment::where('id', $this->id)
                    ->first();

                return $employment ? new EmploymentEntity($employment->toArray()) : null;
            }
        );
    }
}
