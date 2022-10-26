<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория компонента на основе Eloquent для навыков.
 *
 * @method SkillEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method SkillEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Skill extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param RepositoryQueryBuilder $repositoryQueryBuilder Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
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
                $query->where('skills.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('skills.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('skills.text', 'LIKE', '%' . $search . '%');
            });
        }

        return $query;
    }
}
