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
     * Конструктор.
     *
     * @param  string|null  $column  Колонка поиска.
     * @param  mixed|null  $value  Значение поиска.
     * @param  OperatorQuery  $operator  Оператор поиска.
     */
    public function __construct(string $column = null, mixed $value = null, OperatorQuery $operator = OperatorQuery::EQUAL)
    {
        if ($column) {
            $this->set($column, $value, $operator);
        }
    }

    /**
     * Установка условия.
     *
     * @param  string  $column  Колонка.
     * @param  mixed  $value  Значение.
     * @param  OperatorQuery  $operator  Оператор сравнения.
     *
     * @return $this
     */
    public function set(string $column, mixed $value, OperatorQuery $operator = OperatorQuery::EQUAL): self
    {
        $this->column = $column;
        $this->value = $value;
        $this->operator = $operator;

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
}