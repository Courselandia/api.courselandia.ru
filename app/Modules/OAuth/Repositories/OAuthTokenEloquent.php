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
 */
class OAuthTokenEloquent extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder = null): Builder
    {
        $query = $this->newInstance()->newQuery();

        if ($repositoryQueryBuilder) {
            $query = $this->queryHelper($query, $repositoryQueryBuilder);

            $conditions = $repositoryQueryBuilder->getConditions();

            foreach ($conditions as $condition) {
                if ($condition->getColumn() === 'oauth_clients.user_id') {
                    $query->whereHas(
                        'client',
                        function ($query) use ($condition) {
                            /**
                             * @var Builder $query
                             */
                            $query->where('user_id', $condition->getOperator(), $condition->getValue());
                        }
                    );
                }
            }
        }

        return $query;
    }
}
