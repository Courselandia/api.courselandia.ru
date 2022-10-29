<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use DB;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория компонента на основе Eloquent для публикаций.
 *
 * @method PublicationEntity|Entity|null get(RepositoryQueryBuilderPublication $repositoryQueryBuilder = null, Entity $entity = null)
 * @method PublicationEntity[]|Entity[] read(RepositoryQueryBuilderPublication $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilderPublication $repositoryQueryBuilder = null)
 */
class Publication extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param  RepositoryQueryBuilderPublication  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
     */
    protected function query(RepositoryQueryBuilderPublication $repositoryQueryBuilder): Builder
    {
        $query = $this->newInstance()->newQuery();
        $query = $this->queryHelper($query, $repositoryQueryBuilder);
        $year = $repositoryQueryBuilder->getYear();

        if (isset($year)) {
            $query->where(DB::raw("DATE_FORMAT(published_at, '%Y')"), $year);
        }

        return $query;
    }
}
