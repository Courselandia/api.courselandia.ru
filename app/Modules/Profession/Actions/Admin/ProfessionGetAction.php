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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Repositories\Profession;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения профессии.
 */
class ProfessionGetAction extends Action
{
    /**
     * Репозиторий профессий.
     *
     * @var Profession
     */
    private Profession $profession;

    /**
     * ID профессии.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Profession  $profession  Репозиторий профессий.
     */
    public function __construct(Profession $profession)
    {
        $this->profession = $profession;
    }

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?ProfessionEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
            ]);

        $cacheKey = Util::getKey('profession', $query);

        return Cache::tags(['profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->profession->get($query);
            }
        );
    }
}
