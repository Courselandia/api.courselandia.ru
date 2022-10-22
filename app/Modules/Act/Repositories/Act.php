<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Repositories;

use App\Models\Entity;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Act\Entities\Act as ActEntity;

/**
 * Класс репозитория действий на основе Eloquent.
 *
 * @method ActEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method ActEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Act extends Repository
{
    use RepositoryEloquent;
}
