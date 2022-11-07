<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Repositories;

use App\Models\Entity;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Course\Entities\CourseLearn as CourseLearnEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;

/**
 * Класс репозитория чему можно научится на курсе на основе Eloquent.
 *
 * @method CourseLearnEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method CourseLearnEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 */
class CourseLearn extends Repository
{
    use RepositoryEloquent;
}
