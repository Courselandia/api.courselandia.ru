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
use Eloquent;
use Illuminate\Foundation\Auth\User as Authenticatable;
use MongoDb;

/**
 * Абстрактный класс репозитория, для построения собственных репозиториев.
 */
abstract class Repository
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
     * @param  Eloquent|Authenticatable|MongoDb  $model  Модель данного репозитория.
     * @param  Entity  $entity  Сущность данного репозитория.
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
     * @param  Eloquent|Authenticatable|MongoDb  $model  Модель данного репозитория.
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
     * @param  Entity  $entity  Сущность данного репозитория.
     *
     * @return Repository
     */
    protected function setEntity(Entity $entity): Repository
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Получить по первичному ключу.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     * @param  Entity|null  $entity  Сущность.
     *
     * @return Entity|null Данные.
     */
    abstract public function get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null): Entity|null;

    /**
     * Чтение данных.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     * @param  Entity|null  $entity  Сущность.
     *
     * @return Entity[] Массив данных.
     * @throws ParameterInvalidException
     */
    abstract public function read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null): array;

    /**
     * Создание.
     *
     * @param  Entity  $entity  Сущность.
     *
     * @return int|string Вернет ID последней вставленной строки.
     */
    abstract public function create(Entity $entity): int|string;

    /**
     * Обновление.
     *
     * @param  int|string  $id  Id записи для обновления.
     * @param  Entity  $entity  Сущность.
     *
     * @return int|string Вернет ID вставленной строки. Если ошибка, то вернет false.
     */
    abstract public function update(int|string $id, Entity $entity): int|string;

    /**
     * Удаление.
     *
     * @param  int|string|array|null  $id  Id записи для удаления.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    abstract public function destroy(int|string|array $id = null): bool;

    /**
     * Получение нового экземпляра модели.
     *
     * @param  Entity|null  $entity  Сущность.
     * @param  bool  $exists  Определяет есть ли эта запись или нет.
     *
     * @return Eloquent Объект модели данного репозитория.
     */
    abstract public function newInstance(?Entity $entity = null, bool $exists = false): Eloquent;
}
