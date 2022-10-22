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
            $search = $repositoryQueryBuilder->getSearch();

            if ($search) {
                $query->where(function ($query) use ($search) {
                    /**
                     * @var Builder $query
                     */
                    $query->where('id', 'LIKE', '%'.$search.'%')
                        ->orWhere('title', 'LIKE', '%'.$search.'%')
                        ->orWhere('description', 'LIKE', '%'.$search.'%')
                        ->orWhere('url', 'LIKE', '%'.$search.'%');
                });
            }
        }

        return $query;
    }
}
