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
use App\Modules\Course\Entities\CourseFeature as CourseFeatureEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;

/**
 * Класс репозитория особенностей курсов на основе Eloquent.
 *
 * @method CourseFeatureEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method CourseFeatureEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 */
class CourseFeature extends Repository
{
    use RepositoryEloquent;
}
