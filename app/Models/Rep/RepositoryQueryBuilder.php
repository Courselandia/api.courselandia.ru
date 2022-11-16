<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use Util;
use Illuminate\Database\Query\Expression;
use App\Models\Enums\SortDirection;

/**
 * Класс для конструирования запроса к репозиторию.
 */
class RepositoryQueryBuilder
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    private int|string|null $id = null;

    /**
     * Булево значение, если определить как true, то будет получать только активные записи.
     *
     * @var bool
     */
    private ?bool $active = null;

    /**
     * Условия.
     *
     * @var ?array
     */
    private ?array $conditions = null;

    /**
     * Фильтрация.
     *
     * @var ?array
     */
    private ?array $filters = null;

    /**
     * Сортировка.
     *
     * @var ?array
     */
    private ?array $sorts = null;

    /**
     * Отступ вывода.
     *
     * @var ?int
     */
    private ?int $offset = null;

    /**
     * Лимит вывода.
     *
     * @var ?int
     */
    private ?int $limit = null;

    /**
     * Связанные модели
     *
     * @var ?array
     */
    private ?array $relations = null;

    /**
     * Группировка.
     *
     * @var ?array
     */
    private ?array $groups = null;

    /**
     * Выражения для выборки.
     *
     * @var ?array
     */
    private ?array $selects = null;

    /**
     * Конструктор.
     *
     * @param  int|string|null  $id  ID записи.
     * @param  bool|null  $active  Булево значение, если определить как true, то будет получать только активные записи.
     * @param  array|null  $conditions  Условия.
     * @param  array|null  $filters  Фильтрация данных.
     * @param  array|null  $sorts  Массив значений для сортировки.
     * @param  int|null  $offset  Отступ вывода.
     * @param  int|null  $limit  Лимит вывода.
     * @param  array|null  $relations  Массив связанных моделей.
     * @param  array|null  $groups  Массив для группировки.
     * @param  array|null  $selects  Выражения для выборки.
     */
    public function __construct(
        int|string $id = null,
        bool $active = null,
        array $conditions = null,
        array $filters = null,
        array $sorts = null,
        int $offset = null,
        int $limit = null,
        array $relations = null,
        array $groups = null,
        array $selects = null
    ) {
        $this->setId($id);
        $this->setActive($active);
        $this->setConditions($conditions);
        $this->setFilters($filters);
        $this->setSorts($sorts);
        $this->setOffset($offset);
        $this->setLimit($limit);
        $this->setRelations($relations);
        $this->setGroups($groups);
        $this->setSorts($selects);
    }

    /**
     * Получение ID записи.
     *
     * @return int|string|null Вернет ID записи.
     */
    public function getId(): int|string|null
    {
        return $this->id;
    }

    /**
     * Установка ID записи.
     *
     * @param  int|string|null  $id  ID записи.
     *
     * @return $this
     */
    public function setId(int|string|null $id): self
    {
        $this->id = $id;

        return $this;
    }

    //

    /**
     * Получение статуса активности.
     *
     * @return bool|null Вернет статус активности.
     */
    public function getActive(): bool|null
    {
        return $this->active;
    }

    /**
     * Установка статуса активности.
     *
     * @param  bool|null  $active  Статус активности.
     *
     * @return $this
     */
    public function setActive(bool|null $active): self
    {
        $this->active = $active;

        return $this;
    }

    //

    /**
     * Получение условий.
     *
     * @return RepositoryCondition[]|null Вернет условия.
     */
    public function getConditions(): array|null
    {
        return $this->conditions;
    }

    /**
     * Установка условий.
     *
     * @param  RepositoryCondition[]|null  $repositoryConditions  Условия.
     *
     * @return $this
     */
    public function setConditions(?array $repositoryConditions): self
    {
        if ($repositoryConditions) {
            foreach ($repositoryConditions as $repositoryCondition) {
                $this->addCondition($repositoryCondition);
            }
        }

        return $this;
    }

    /**
     * Добавление условия.
     *
     * @param  RepositoryCondition  $repositoryCondition  Условия репозитория.
     *
     * @return $this
     */
    public function addCondition(RepositoryCondition $repositoryCondition): self
    {
        $this->conditions[] = $repositoryCondition;

        return $this;
    }

    /**
     * Правка условия.
     *
     * @param  RepositoryCondition  $repositoryCondition  Условия репозитория.
     *
     * @return $this
     */
    public function editCondition(RepositoryCondition $repositoryCondition): self
    {
        $conditions = $this->getConditions();

        if ($conditions) {
            foreach ($conditions as $condition) {
                if ($condition->getColumn() === $repositoryCondition->getColumn()) {
                    $condition->set(
                        $repositoryCondition->getColumn(),
                        $repositoryCondition->getValue(),
                        $repositoryCondition->getOperator()
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Очистка условий.
     *
     * @return $this
     */
    public function clearConditions(): self
    {
        $this->conditions = null;

        return $this;
    }

    //

    /**
     * Получение фильтрации.
     *
     * @return RepositoryFilter[]|null Вернет условия фильтров.
     */
    public function getFilters(): array|null
    {
        return $this->filters;
    }

    /**
     * Установка условий фильтрации.
     *
     * @param  RepositoryFilter[]|null  $repositoryFilters  Условия фильтрации.
     *
     * @return $this
     */
    public function setFilters(?array $repositoryFilters): self
    {
        if ($repositoryFilters) {
            foreach ($repositoryFilters as $repositoryFilter) {
                $this->addFilter($repositoryFilter);
            }
        }

        return $this;
    }

    /**
     * Добавление условия фильтрации.
     *
     * @param  RepositoryFilter  $repositoryFilter  Условия фильтрации.
     *
     * @return $this
     */
    public function addFilter(RepositoryFilter $repositoryFilter): self
    {
        $this->filters[] = $repositoryFilter;

        return $this;
    }

    /**
     * Правка условия фильтрации.
     *
     * @param  RepositoryFilter  $repositoryFilter  Условия фильтрации.
     *
     * @return $this
     */
    public function editFilter(RepositoryFilter $repositoryFilter): self
    {
        $filters = $this->getFilters();

        if ($filters) {
            foreach ($filters as $filter) {
                if ($filter->getColumn() === $repositoryFilter->getColumn()) {
                    $filter->set($repositoryFilter->getColumn(), $repositoryFilter->getValue());
                }
            }
        }

        return $this;
    }

    /**
     * Очистка условий фильтрации.
     *
     * @return $this
     */
    public function clearFilters(): self
    {
        $this->filters = null;

        return $this;
    }

    //

    /**
     * Получение сортировки.
     *
     * @return array|null Вернет сортировку.
     */
    public function getSorts(): array|null
    {
        return $this->sorts;
    }

    /**
     * Установка сортировки.
     *
     * @param  array|null  $sorts  Сортировка.
     *
     * @return $this
     */
    public function setSorts(?array $sorts): self
    {
        if ($sorts) {
            foreach ($sorts as $column => $direction) {
                if (is_string($direction)) {
                    $direction = SortDirection::from($direction);
                }

                $this->addSort($column, $direction);
            }
        }

        return $this;
    }

    /**
     * Добавление сортировки.
     *
     * @param  string|Expression  $column  Колонка.
     * @param  SortDirection  $direction  Направление.
     *
     * @return $this
     */
    public function addSort(string|Expression $column, SortDirection $direction = SortDirection::ASC): self
    {
        $this->sorts[$column] = $direction;

        return $this;
    }

    /**
     * Правка сортировки.
     *
     * @param  string|Expression  $column  Колонка.
     * @param  SortDirection  $direction  Направление.
     *
     * @return $this
     */
    public function editSorts(string|Expression $column, SortDirection $direction): self
    {
        if (isset($this->sorts[$column])) {
            $this->sorts[$column] = $direction;
        }

        return $this;
    }

    /**
     * Очистка сортировки.
     *
     * @return $this
     */
    public function clearSorts(): self
    {
        $this->sorts = null;

        return $this;
    }

    //

    /**
     * Получение отступа.
     *
     * @return int|null Вернет отступ.
     */
    public function getOffset(): int|null
    {
        return $this->offset;
    }

    /**
     * Установка отступа.
     *
     * @param  int|null  $offset  Отступ.
     *
     * @return $this
     */
    public function setOffset(int|null $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    //

    /**
     * Получение лимита.
     *
     * @return int|null Вернет лимит.
     */
    public function getLimit(): int|null
    {
        return $this->limit;
    }

    /**
     * Установка лимита.
     *
     * @param  int|null  $limit  Лимит.
     *
     * @return $this
     */
    public function setLimit(int|null $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    //

    /**
     * Получение отношений.
     *
     * @return array|null Вернет отношения.
     */
    public function getRelations(): array|null
    {
        return $this->relations;
    }

    /**
     * Установка отношения.
     *
     * @param  array|null  $relations  Отношения.
     *
     * @return $this
     */
    public function setRelations(?array $relations): self
    {
        if ($relations) {
            if (Util::isAssoc($relations)) {
                foreach ($relations as $relation => $queryBuilder) {
                    if ($queryBuilder instanceof RepositoryQueryBuilder) {
                        $this->addRelation($relation, $queryBuilder);
                    } else {
                        $this->addRelation($queryBuilder);
                    }
                }
            } else {
                foreach ($relations as $relation) {
                    $this->addRelation($relation);
                }
            }
        }

        return $this;
    }

    /**
     * Добавление отношения.
     *
     * @param string $relation Отношение.
     * @param RepositoryQueryBuilder|null $queryBuilder Подзапрос для этого отношения.
     *
     * @return $this
     */
    public function addRelation(string $relation, RepositoryQueryBuilder $queryBuilder = null): self
    {
        $this->relations[] = [$relation, $queryBuilder];

        return $this;
    }

    /**
     * Правка отношения.
     *
     * @param  string  $relation  Отношение.
     * @param RepositoryQueryBuilder|null $queryBuilder Подзапрос для этого отношения.
     *
     * @return $this
     */
    public function editRelation(string $relation, RepositoryQueryBuilder $queryBuilder = null): self
    {
        if (isset($this->relations[$relation])) {
            $this->relations[] = [$relation, $queryBuilder];
        }

        return $this;
    }

    /**
     * Очистка отношений.
     *
     * @return $this
     */
    public function clearRelations(): self
    {
        $this->relations = null;

        return $this;
    }

    //

    /**
     * Получение группировок.
     *
     * @return array|null Вернет группировки.
     */
    public function getGroups(): array|null
    {
        return $this->groups;
    }

    /**
     * Установка группировки.
     *
     * @param  array|null  $groups  Группировки.
     *
     * @return $this
     */
    public function setGroups(?array $groups): self
    {
        if ($groups) {
            foreach ($groups as $group) {
                $this->addGroup($group);
            }
        }

        return $this;
    }

    /**
     * Добавление группировки.
     *
     * @param  string|Expression  $group  Группировки.
     *
     * @return $this
     */
    public function addGroup(string|Expression $group): self
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Правка группировки.
     *
     * @param  mixed  $group  Группировка.
     *
     * @return $this
     */
    public function editGroup(mixed $group): self
    {
        if (isset($this->groups[$group])) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * Очистка группировок.
     *
     * @return $this
     */
    public function clearGroups(): self
    {
        $this->groups = null;

        return $this;
    }

    //

    /**
     * Получение выборки.
     *
     * @return array|null Вернет выборку.
     */
    public function getSelects(): array|null
    {
        return $this->selects;
    }

    /**
     * Установка выборки.
     *
     * @param  array|null  $selects  Выборки.
     *
     * @return $this
     */
    public function setSelects(?array $selects): self
    {
        if($selects) {
            foreach ($selects as $select) {
                $this->addSelect($select);
            }
        }

        return $this;
    }

    /**
     * Добавление выборки.
     *
     * @param  string|Expression  $select  Выборка.
     *
     * @return $this
     */
    public function addSelect(string|Expression $select): self
    {
        $this->selects[] = $select;

        return $this;
    }

    /**
     * Правка выборки.
     *
     * @param  mixed  $select  Выборка.
     *
     * @return $this
     */
    public function editSelect(mixed $select): self
    {
        if (isset($this->selects[$select])) {
            $this->selects[] = $select;
        }

        return $this;
    }

    /**
     * Очистка выборки.
     *
     * @return $this
     */
    public function clearSelects(): self
    {
        $this->selects = null;

        return $this;
    }
}
