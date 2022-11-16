<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Site\School;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Repositories\School;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения школы.
 */
class SchoolGetAction extends Action
{
    /**
     * Репозиторий школ.
     *
     * @var School
     */
    private School $school;

    /**
     * ID школы.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  School  $school  Репозиторий школ.
     */
    public function __construct(School $school)
    {
        $this->school = $school;
    }

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
            ->setActive(true)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('school', $query);

        return Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->school->get($query);
            }
        );
    }
}
