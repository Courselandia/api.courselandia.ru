<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

/**
 * Трейт репозитория работающий с MongoDb.
 */
trait RepositoryMongoDb
{
    use RepositoryEloquent;
}
