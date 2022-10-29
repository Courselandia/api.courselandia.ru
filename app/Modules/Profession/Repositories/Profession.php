<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория компонента на основе Eloquent для профессий.
 *
 * @method ProfessionEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method ProfessionEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Profession extends Repository
{
    use RepositoryEloquent;
}
