<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория компонента на основе Eloquent для направлений.
 *
 * @method DirectionEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method DirectionEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Direction extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param RepositoryQueryBuilder $repositoryQueryBuilder Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder): Builder
    {
        $query = $this->newInstance()->newQuery();
        $query = $this->queryHelper($query, $repositoryQueryBuilder);
        $search = $repositoryQueryBuilder->getSearch();

        if ($search) {
            $query->where(function ($query) use ($search) {
                /**
                 * @var Builder $query
                 */
                $query->where('directions.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('directions.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('directions.text', 'LIKE', '%' . $search . '%');
            });
        }

        return $query;
    }
}
