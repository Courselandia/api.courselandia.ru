<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Repositories;

use App\Models\Entity;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use App\Modules\Alert\Entities\Alert as AlertEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория предупреждений на основе Eloquent.
 *
 * @method AlertEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method AlertEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Alert extends Repository
{
    use RepositoryEloquent;
}
