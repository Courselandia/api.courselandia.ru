<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
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
}
