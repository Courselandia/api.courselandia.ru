<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Repositories;

use App\Models\Entity;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Modules\OAuth\Entities\OAuthToken;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория токенов на основе Eloquent.
 *
 * @method OAuthToken|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method OAuthToken[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method Builder query(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 * @method int|string create(OAuthToken $token)
 * @method int|string update(int|string $id, OAuthToken $token)
 */
class OAuthTokenEloquent extends Repository
{
    use RepositoryEloquent;
}
