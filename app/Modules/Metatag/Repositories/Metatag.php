<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Repositories;

use App\Models\Entity;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;

/**
 * Класс репозитория предупреждений на основе Eloquent.
 *
 * @method MetatagEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method MetatagEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Metatag extends Repository
{
    use RepositoryEloquent;
}
