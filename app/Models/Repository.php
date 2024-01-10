<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Eloquent;
use MongoDb;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Абстрактный класс репозитория, для построения собственных репозиториев.
 */
class Repository
{
    /**
     * Модель данного репозитория.
     *
     * @var Eloquent|Authenticatable|MongoDb
     */
    private Eloquent|Authenticatable|MongoDb $model;

    /**
     * Сущность данного репозитория.
     *
     * @var Entity
     */
    private Entity $entity;

    /**
     * Конструктор.
     *
     * @param Eloquent|Authenticatable|MongoDb $model Модель данного репозитория.
     * @param Entity $entity Сущность данного репозитория.
     */
    public function __construct(Eloquent|Authenticatable|MongoDb $model, Entity $entity)
    {
        $this->setModel($model);
        $this->setEntity($entity);
    }

    /**
     * Получение модели этого репозитория.
     *
     * @return Eloquent|Authenticatable|MongoDb Модель данного репозитория.
     */
    public function getModel(): Eloquent|Authenticatable|MongoDb
    {
        return $this->model;
    }

    /**
     * Установка модели этого репозитория.
     *
     * @param Eloquent|Authenticatable|MongoDb $model Модель данного репозитория.
     *
     * @return Repository
     */
    protected function setModel(Eloquent|Authenticatable|MongoDb $model): Repository
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Получение сущности этого репозитория.
     *
     * @return Entity Сущность данного репозитория.
     */
    public function getEntity(): Entity
    {
        return $this->entity;
    }

    /**
     * Установка сущности этого репозитория.
     *
     * @param Entity $entity Сущность данного репозитория.
     *
     * @return Repository
     */
    protected function setEntity(Entity $entity): Repository
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Получение нового экземпляра модели.
     *
     * @param Entity|null $entity Данные для обновления.
     * @param bool $exists Определяет есть ли эта запись или нет.
     *
     * @return Eloquent Объект модели данного репозитория.
     * @throws ParameterInvalidException
     */
    public function newInstance(?Entity $entity = null, bool $exists = false): Eloquent
    {
        $model = clone $this->getModel();

        if ($entity) {
            $model->newInstance($entity->toArray(), $exists);
            $keyName = $model->getKeyName();

            if ($exists && isset($data[$keyName])) {
                $methodGetKeyName = 'get' . ucfirst($keyName) . '()';
                $model->{$keyName} = $entity->$methodGetKeyName;
            }
        }

        return $model;
    }
}
