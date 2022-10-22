<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models\Rep;

/**
 * Фильтрация для репозитория.
 */
class RepositoryFilter
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
     * Конструктор.
     *
     * @param  string|null  $column  Колонка поиска.
     * @param  mixed|null  $value  Значение поиска.
     */
    public function __construct(string $column = null, mixed $value = null)
    {
        if ($column && $value) {
            $this->set($column, $value);
        }
    }

    /**
     * Конвертирует массив фильтров в объект фильтров.
     *
     * @param  array|null  $filters  Фильтры.
     *
     * @return RepositoryFilter[] Готовый фильтр для репозитория
     */
    public static function getFilters(array $filters = null): array
    {
        $result = [];

        if ($filters) {
            foreach ($filters as $column => $value) {
                $filter = new RepositoryFilter();
                $filter->set($column, $value);

                $result[] = $filter;
            }
        }

        return $result;
    }

    /**
     * Установка условия.
     *
     * @param  string  $column  Колонка.
     * @param  mixed  $value  Значение.
     *
     * @return $this
     */
    public function set(string $column, mixed $value): self
    {
        $this->column = $column;
        $this->value = $value;

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
}
