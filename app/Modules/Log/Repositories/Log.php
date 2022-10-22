<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Repositories;

use App\Models\Entity;
use App\Models\Rep\Repository;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryMongoDb;
use App\Modules\Log\Entities\Log as LogEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория для логирования.
 *
 * @method LogEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method LogEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Log extends Repository
{
    use RepositoryMongoDb;

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
                    $query->where($this->newInstance()->getKeyName(), 'LIKE', '%'.$search.'%')
                        ->orWhere('level_name', 'LIKE', '%'.$search.'%')
                        ->orWhere('message', 'LIKE', '%'.$search.'%')
                        ->orWhere('context', 'LIKE', '%'.$search.'%');
                });
            }
        }

        return $query;
    }
}
