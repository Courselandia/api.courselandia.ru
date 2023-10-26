<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Modules\OAuth\Entities\OAuthRefresh;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория токенов обновления на основе Eloquent.
 *
 * @method OAuthRefresh|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method OAuthRefresh[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int|string create(OAuthRefresh $token)
 * @method int|string update(int|string $id, OAuthRefresh $token)
 */
class OAuthRefreshTokenEloquent extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param RepositoryQueryBuilder|null $repositoryQueryBuilder Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder = null): Builder
    {
        $query = $this->newInstance()->newQuery();

        if ($repositoryQueryBuilder) {
            $query = $this->queryHelper($query, $repositoryQueryBuilder);

            $conditions = $repositoryQueryBuilder->getConditions();

            foreach ($conditions as $condition) {
                if ($condition->getColumn() === 'oauth_tokens.user_id') {
                    $query->whereHas(
                        'token',
                        function ($query) use ($condition) {
                            /**
                             * @var Builder $query
                             */
                            $query->where('oauth_tokens.user_id', $condition->getOperator()->value, $condition->getValue());
                        }
                    );
                }
            }
        }

        return $query;
    }
}
