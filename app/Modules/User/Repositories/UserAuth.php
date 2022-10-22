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
use App\Modules\User\Entities\UserAuth as UserAuthEntity;
use App\Models\Rep\RepositoryEloquent;

/**
 * Класс репозитория для хранения аутентификаций пользователя на основе Eloquent.
 *
 * @method UserAuthEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method UserAuthEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class UserAuth extends Repository
{
    use RepositoryEloquent;
}
