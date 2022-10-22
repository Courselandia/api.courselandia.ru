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
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\OAuth\Entities\OAuthClient;

/**
 * Класс репозитория клиентов на основе Eloquent.
 *
 * @method OAuthClient|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method OAuthClient[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class OAuthClientEloquent extends Repository
{
    use RepositoryEloquent;
}
