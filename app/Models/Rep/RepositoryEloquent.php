<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use DB;
use Eloquent;
use App\Models\Entity;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Exceptions\RecordNotExistException;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Трейт репозитория работающий с Eloquent.
 */
trait RepositoryEloquent
{
    /**
     * Получение запроса на выборку.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder = null): Builder
    {
        return $this->queryHelper($this->newInstance()->newQuery(), $repositoryQueryBuilder);
    }

    /**
     * Получить по первичному ключу.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     * @param  Entity|null  $entity  Класс сущности данных.
     *
     * @return Entity|null Данные.
     * @throws ParameterInvalidException
     */
    public function get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null): Entity|null
    {
        $query = $this->query($repositoryQueryBuilder);
        $result = $query->first();

        if ($result) {
            $item = $result->toArray();
            $keyName = $this->newInstance()->getKeyName();
            $item['id'] = $item[$keyName];

            return $entity ? clone $entity->set($item) : clone $this->getEntity()->set($item);
        }

        return null;
    }

    /**
     * Чтение данных.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     * @param  Entity|null  $entity  Класс сущности данных.
     *
     * @return Entity[] Массив данных.
     * @throws ParameterInvalidException
     */
    public function read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null): array
    {
        $query = $this->query($repositoryQueryBuilder);
        $items = $query->get()->toArray();
        $result = [];

        foreach ($items as $item)
        {
            $keyName = $this->newInstance()->getKeyName();

            if (isset($item[$keyName])) {
                $item['id'] = $item[$keyName];
            }

            $entity = $entity ? clone $entity : clone $this->getEntity();

            $result[] = $entity->set($item);
        }

        return $result;
    }

    /**
     * Подсчет общего количества записей.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return int Количество.
     * @throws ParameterInvalidException
     */
    public function count(RepositoryQueryBuilder $repositoryQueryBuilder = null): int
    {
        $repositoryQueryBuilder = clone $repositoryQueryBuilder;
        $repositoryQueryBuilder->setOffset(null);
        $repositoryQueryBuilder->setLimit(null);

        $query = $this->query($repositoryQueryBuilder);

        return  $query->count();
    }

    /**
     * Создание.
     *
     * @param  Entity  $entity  Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     * @throws ParameterInvalidException
     */
    public function create(Entity $entity): int|string
    {
        $model = $this->newInstance();
        $model = $model->create($entity->toArray());

        return $model->getKey();
    }

    /**
     * Обновление.
     *
     * @param  int|string  $id  Id записи для обновления.
     * @param  Entity  $entity  Данные для добавления.
     *
     * @return int|string Вернет ID вставленной строки. Если ошибка, то вернет false.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function update(int|string $id, Entity $entity): int|string
    {
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->update($entity->toArray());

            return $id;
        }

        throw new RecordNotExistException(trans('models.repositoryEloquent.record_not_exist'));
    }

    /**
     * Удаление.
     *
     * @param  int|string|array|null  $id  Id записи для удаления.
     * @param  bool  $force  Просим удалить записи полностью.
     *
     * @return bool Вернет булево значение успешности операции.
     * @throws ParameterInvalidException
     */
    public function destroy(int|string|array $id = null, bool $force = false): bool
    {
        $query = $this->newInstance()->newQuery();

        if ($id) {
            $query = $query->whereIn($this->newInstance()->getKeyName(), is_array($id) ? $id : [$id]);
        }

        $models = $query->get();

        if ($models) {
            foreach ($models as $model) {
                if ($force) {
                    $model->forceDelete();
                } else {
                    $model->delete();
                }
            }
        }

        return true;
    }

    /**
     * Получение нового экземпляра модели.
     *
     * @param  Entity|null  $entity  Данные для обновления.
     * @param  bool  $exists  Определяет есть ли эта запись или нет.
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
                $methodGetKeyName = 'get'.ucfirst($keyName).'()';
                $model->{$keyName} = $entity->$methodGetKeyName;
            }
        }

        return $model;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: ID.
     *
     * @param  Builder  $query  Запрос.
     * @param  int|string  $id  ID записи.
     *
     * @return Builder Вернет запрос.
     * @throws ParameterInvalidException
     */
    protected function queryHelperId(Builder $query, int|string $id): Builder
    {
        $query->where($this->newInstance()->getKeyName(), $id);

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Активность.
     *
     * @param  Builder  $query  Запрос.
     * @param  bool  $active  Булево значение, если определить как true, то будет получать только активные записи.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperActive(Builder $query, bool $active): Builder
    {
        if ($active === true) {
            $query->active();
        } else {
            $query->notActive();
        }

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Условия.
     *
     * @param  Builder|Relation  $query  Запрос.
     * @param  RepositoryCondition[]  $conditions  Условия.
     *
     * @return Builder|Relation Вернет запрос.
     * @throws ParameterInvalidException
     */
    protected function queryHelperConditions(Builder|Relation $query, array $conditions): Builder|Relation
    {
        $model = $this->newInstance();
        $columns = $model->getFillable();

        foreach ($conditions as $condition) {
            if ($condition->getRelation()) {
                $relationColumns = $model->{$condition->getRelation()}()->getModel()->getFillable();

                if (in_array($condition->getColumn(), $relationColumns)) {
                    $query->whereHas($condition->getRelation(), function ($q) use ($condition) {
                        $q->where($condition->getColumn(), $condition->getOperator()->value, $condition->getValue());
                    });
                }
            } elseif (in_array($condition->getColumn(), $columns)) {
                $query->where($condition->getColumn(), $condition->getOperator()->value, $condition->getValue());
            }
        }

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Фильтрация.
     *
     * @param  Builder  $query  Запрос.
     * @param  RepositoryFilter[]|null  $filters  Фильтрация.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperFilters(Builder $query, array $filters = null): Builder
    {
        if ($filters) {
            $result = [];

            foreach ($filters as $filter) {
                $result[$filter->getColumn()] = $filter->getValue();
            }

            $query->filter($result);
        }

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Сортировка.
     *
     * @param  Builder|Relation  $query  Запрос.
     * @param  array  $sorts  Массив значений для сортировки.
     *
     * @return Builder|Relation Вернет запрос.
     */
    protected function queryHelperSorts(Builder|Relation $query, array $sorts): Builder|Relation
    {
        foreach ($sorts as $column => $order) {
            $sorts[$column] = $order->value;
        }

        $query->sorted($sorts);

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Отступ.
     *
     * @param  Builder  $query  Запрос.
     * @param  int  $offset  Отступ вывода.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperOffset(Builder $query, int $offset): Builder
    {
        return $query->offset($offset);
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Лимит.
     *
     * @param  Builder  $query  Запрос.
     * @param  int  $limit  Лимит вывода.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperLimit(Builder $query, int $limit): Builder
    {
        return $query->limit($limit);
    }

    /**
     * Получение полей для сортировки на основе названия таблицы.
     *
     * @param  array  $sorts  Массив сортировок.
     * @param  string  $nameTable  Название таблицы.
     *
     * @return array|null Вернет массив сортировки.
     */
    private function getFieldsForSortingBaseTableName(array $sorts, string $nameTable): ?array
    {
        $result = [];

        foreach ($sorts as $filed => $direction) {
            if (str_contains($filed, $nameTable)) {
                $key = str_replace($nameTable.'.', '', $filed);
                $result[$key] = $direction;
            }
        }

        return count($result) ? $result : null;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Отношения.
     *
     * @param  Builder  $query  Запрос.
     * @param  array  $relations  Массив отношений.
     * @param  array|null  $sorts  Массив сортировок.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperRelations(Builder $query, array $relations, array $sorts = null): Builder
    {
        for ($i = 0; $i < count($relations); $i++) {
            [$nameTable, $queryBuilder] = $relations[$i];

            $query->with(
                [
                    $nameTable => function ($query) use ($sorts, $nameTable, $queryBuilder) {
                        if ($sorts) {
                            $ordersBy = $this->getFieldsForSortingBaseTableName($sorts, $nameTable);

                            if ($ordersBy) {
                                $this->queryHelperSorts($query, $ordersBy);
                            }
                        }

                        if ($queryBuilder) {
                            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class;

                            $this->queryHelperConditions($query, $queryBuilder->getConditions());
                        }
                    }
                ]
            );
        }

        return $query;
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: Группировка.
     *
     * @param  Builder  $query  Запрос.
     * @param  array  $groups  Массив для группировки.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperGroups(Builder $query, array $groups): Builder
    {
        $toGroups = [];

        for ($i = 0; $i < count($groups); $i++) {
            $toGroups[] = $groups[$i];
        }

        return $query->groupBy($toGroups);
    }

    /**
     * Помощник для формирования стандартных свойств для запроса: выборка.
     *
     * @param  Builder  $query  Запрос.
     * @param  array  $selects  Выражения для выборки.
     *
     * @return Builder Вернет запрос.
     */
    protected function queryHelperSelects(Builder $query, array $selects): Builder
    {
        $queryStrRawSelect = '';

        for ($i = 0; $i < count($selects); $i++) {
            if ($queryStrRawSelect) {
                $queryStrRawSelect .= ', ';
            }

            $queryStrRawSelect .= $selects[$i];
        }

        return $query->select(DB::raw($queryStrRawSelect));
    }

    /**
     * Помощник для формирования стандартных свойств для запроса.
     *
     * @param  RepositoryQueryBuilder|null  $repositoryQueryBuilder  Конструктор запроса к репозиторию.
     *
     * @return Builder Вернет запрос.
     * @throws ParameterInvalidException
     */
    protected function queryHelper(Builder $query, RepositoryQueryBuilder $repositoryQueryBuilder = null): Builder
    {
        if ($repositoryQueryBuilder) {
            if ($repositoryQueryBuilder->getId()) {
                $query = $this->queryHelperId($query, $repositoryQueryBuilder->getId());
            }

            if ($repositoryQueryBuilder->getActive() !== null) {
                $query = $this->queryHelperActive($query, $repositoryQueryBuilder->getActive());
            }
            if ($repositoryQueryBuilder->getConditions()) {
                $query = $this->queryHelperConditions($query, $repositoryQueryBuilder->getConditions());
            }

            if ($repositoryQueryBuilder->getSorts()) {
                $query = $this->queryHelperSorts($query, $repositoryQueryBuilder->getSorts());
            }

            if ($repositoryQueryBuilder->getFilters()) {
                $query = $this->queryHelperFilters($query, $repositoryQueryBuilder->getFilters());
            }

            if ($repositoryQueryBuilder->getOffset()) {
                $query = $this->queryHelperOffset($query, $repositoryQueryBuilder->getOffset());
            }
            if ($repositoryQueryBuilder->getLimit()) {
                $query = $this->queryHelperLimit($query, $repositoryQueryBuilder->getLimit());
            }

            if ($repositoryQueryBuilder->getRelations()) {
                $query = $this->queryHelperRelations(
                    $query,
                    $repositoryQueryBuilder->getRelations(),
                    $repositoryQueryBuilder->getSorts(),
                );
            }

            if ($repositoryQueryBuilder->getGroups()) {
                $query = $this->queryHelperGroups($query, $repositoryQueryBuilder->getGroups());
            }

            if ($repositoryQueryBuilder->getSelects()) {
                $query = $this->queryHelperSelects($query, $repositoryQueryBuilder->getSelects());
            }
        }

        return $query;
    }
}
