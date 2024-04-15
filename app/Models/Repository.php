<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Eloquent;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Абстрактный класс репозитория, для построения собственных репозиториев.
 */
class Repository
{
    /**
     * Модель данного репозитория.
     *
     * @var Eloquent|Authenticatable|Model
     */
    private Eloquent|Authenticatable|Model $model;

    /**
     * Название класса сущности данного репозитория.
     *
     * @var string
     */
    private string $entity;

    /**
     * Конструктор.
     *
     * @param Eloquent|Authenticatable|Model $model Модель данного репозитория.
     * @param string $entity Сущность данного репозитория.
     */
    public function __construct(Eloquent|Authenticatable|Model $model, string $entity)
    {
        $this->setModel($model);
        $this->setEntity($entity);
    }

    /**
     * Получение модели этого репозитория.
     *
     * @return Eloquent|Authenticatable|Model Модель данного репозитория.
     */
    public function getModel(): Eloquent|Authenticatable|Model
    {
        return $this->model;
    }

    /**
     * Установка модели этого репозитория.
     *
     * @param Eloquent|Authenticatable|Model $model Модель данного репозитория.
     *
     * @return Repository
     */
    protected function setModel(Eloquent|Authenticatable|Model $model): Repository
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Получение сущности этого репозитория.
     *
     * @params array $params Значения для сущности.
     *
     * @return Entity Сущность данного репозитория.
     */
    public function getEntity(array $params): Entity
    {
        /**
         * @var Entity $nameClass
         */
        $nameClass = $this->entity;

        return $nameClass::from($params);
    }

    /**
     * Установка сущности этого репозитория.
     *
     * @param string $entity Название класса сущности данного репозитория.
     *
     * @return Repository
     */
    protected function setEntity(string $entity): Repository
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
