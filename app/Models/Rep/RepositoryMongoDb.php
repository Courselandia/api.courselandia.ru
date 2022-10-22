<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use Illuminate\Database\Eloquent\Builder;
use ReflectionException;

/**
 * Трейт репозитория работающий с MongoDb.
 */
trait RepositoryMongoDb
{
    use RepositoryEloquent;
}
