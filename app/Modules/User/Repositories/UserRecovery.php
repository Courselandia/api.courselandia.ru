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
use App\Modules\User\Entities\UserRecovery as UserRecoveryEntity;
use App\Models\Rep\RepositoryEloquent;

/**
 * Класс репозитория восстановления пароля пользователей на основе Eloquent.
 *
 * @method UserRecoveryEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method UserRecoveryEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class UserRecovery extends Repository
{
    use RepositoryEloquent;
}
