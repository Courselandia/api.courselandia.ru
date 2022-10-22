<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Repositories;

use App\Models\Entity;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Modules\User\Entities\UserRole as UserRoleEntity;

/**
 * Класс репозитория для ролей на основе Eloquent.
 *
 * @method UserRoleEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method UserRoleEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class UserRole extends Repository
{
    use RepositoryEloquent;
}
