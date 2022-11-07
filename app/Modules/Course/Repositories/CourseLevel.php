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
use App\Modules\Course\Entities\CourseLevel as CourseLevelEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;

/**
 * Класс репозитория уровней курса на основе Eloquent.
 *
 * @method CourseLevelEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method CourseLevelEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 */
class CourseLevel extends Repository
{
    use RepositoryEloquent;
}
