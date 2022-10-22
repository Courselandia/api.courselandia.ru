<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Трейт для добавления возможности работать с параметрами.
 */
trait Parameter
{
    /**
     * Параметры.
     *
     * @var array
     */
    private array $parameters = [];

    /**
     * Добавление параметра.
     *
     * @param  string  $index  Индекс параметра.
     * @param  mixed  $value  Значение параметра.
     *
     * @return Parameter Вернет текущую модель.
     */
    public function addParameter(string $index, mixed $value): static
    {
        $this->parameters[$index] = $value;

        return $this;
    }

    /**
     * Добавление всех параметров.
     *
     * @param  mixed  $values  Массив параметров.
     *
     * @return Parameter|Action Вернет текущую модель.
     */
    public function setParameters(array $values): Action|static
    {
        $this->parameters = $values;

        return $this;
    }

    /**
     * Удаление всех параметров.
     *
     * @return Parameter Вернет текущую модель.
     */
    public function clearParameters(): static
    {
        $this->parameters = [];

        return $this;
    }

    /**
     * Добавление конкретного параметра.
     *
     * @param  string  $index  Индекс параметра.
     *
     * @return Parameter Вернет текущую модель.
     */
    public function clearParameter(string $index): static
    {
        if (isset($this->parameters[$index])) {
            unset($this->parameters[$index]);
        }

        return $this;
    }

    /**
     * Возвращает все параметры.
     *
     * @return array Массив параметров.
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Возвращает параметр по индексу.
     *
     * @param  string  $index  Индекс параметра.
     * @param  mixed  $default  Значение по умолчанию.
     *
     * @return mixed Вернет параметр.
     */
    public function getParameter(string $index, mixed $default = null): mixed
    {
        if (isset($this->parameters[$index])) {
            return $this->parameters[$index];
        } elseif (isset($default)) {
            return $default;
        }

        return null;
    }
}
