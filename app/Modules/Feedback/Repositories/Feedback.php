<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Repositories;

use App\Models\Entity;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория для обратной связи на основе Eloquent.
 *
 * @method FeedbackEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method FeedbackEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Feedback extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param  RepositoryQueryBuilder  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder): Builder
    {
        $query = $this->newInstance()->newQuery();
        $query = $this->queryHelper($query, $repositoryQueryBuilder);

        $search = $repositoryQueryBuilder->getSearch();

        if ($search) {
            $query->where(function ($query) use ($search) {
                /**
                 * @var Builder $query
                 */
                $query->where('id', 'LIKE', '%'.$search.'%')
                    ->orWhere('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('phone', 'LIKE', '%'.$search.'%')
                    ->orWhere('email', 'LIKE', '%'.$search.'%');
            });
        }

        return $query;
    }
}
