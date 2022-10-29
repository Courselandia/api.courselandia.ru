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
}
