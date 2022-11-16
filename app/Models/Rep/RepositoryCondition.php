<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

use App\Models\Enums\OperatorQuery;

/**
 * Условия для репозитория.
 */
class RepositoryCondition
{
    /**
     * Колонка поиска.
     *
     * @var string
     */
    private string $column;

    /**
     * Значение поиска.
     *
     * @var mixed
     */
    private mixed $value;

    /**
     * Оператор поиска.
     *
     * @var OperatorQuery
     */
    private OperatorQuery $operator;

    /**
     * Отношение к которому нужно применить условие.
     *
     * @var string|null
     */
    private ?string $relation = null;

    /**
     * Конструктор.
     *
     * @param string|null $column Колонка поиска.
     * @param mixed|null $value Значение поиска.
     * @param OperatorQuery $operator Оператор поиска.
     * @param string|null $relation Отношение к которому нужно применить это условие.
     */
    public function __construct(string $column = null, mixed $value = null, OperatorQuery $operator = OperatorQuery::EQUAL, string $relation = null)
    {
        if ($column) {
            $this->set($column, $value, $operator, $relation);
        }
    }

    /**
     * Установка условия.
     *
     * @param  string  $column  Колонка.
     * @param  mixed  $value  Значение.
     * @param  OperatorQuery  $operator  Оператор сравнения.
     * @param string|null $relation Отношение к которому нужно применить это условие.
     *
     * @return $this
     */
    public function set(string $column, mixed $value, OperatorQuery $operator = OperatorQuery::EQUAL, string $relation = null): self
    {
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;
        $this->relation = $relation;

        return $this;
    }

    /**
     * Получение колонки.
     *
     * @return string Название колонки.
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * Получение значения.
     *
     * @return mixed Значение.
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Получение оператора сравнения.
     *
     * @return OperatorQuery Оператор сравнения.
     */
    public function getOperator(): OperatorQuery
    {
        return $this->operator;
    }

    /**
     * Получение отношение, к которому нужно применить условие.
     *
     * @return string|null Название колонки.
     */
    public function getRelation(): ?string
    {
        return $this->relation;
    }
}
