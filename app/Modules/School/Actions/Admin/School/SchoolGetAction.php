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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?SchoolEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('school', $query, $this->id, 'metatag');

        return Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                $school = School::where('id', $this->id)->first();

                return $school ? new SchoolEntity($school->toArray()) : null;
            }
        );
    }
}
