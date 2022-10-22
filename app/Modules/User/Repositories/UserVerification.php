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
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;
use App\Models\Rep\RepositoryEloquent;

/**
 * Класс репозитория верификации пользователей на основе Eloquent.
 *
 * @method UserVerificationEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method UserVerificationEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class UserVerification extends Repository
{
    use RepositoryEloquent;
}
