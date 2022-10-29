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
}
