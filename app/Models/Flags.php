<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Трейт для модели которая поддерживает систему флагов.
 */
trait Flags
{
    /**
     * Загрузка флаговой системы.
     *
     * @return void
     */
    public static function bootFlagsTrait(): void
    {
        static::saving(function (Model $model) {
            $model->flags = empty($model->flags) ? [] : $model->flags;
        });
    }

    /**
     * Перегрузка метода заполнения данными модели.
     *
     * @return array Вернет заполненную модель.
     */
    public function getFillable(): array
    {
        if (!in_array('flags', $this->fillable)) {
            $this->fillable[] = 'flags';
        }

        return parent::getFillable();
    }

    /**
     * Перегрузка метода заполнения данными модели.
     *
     * @return array Вернет заполненную модель.
     */
    public function getCasts(): array
    {
        if (!isset($this->casts['flags'])) {
            $this->casts['flags'] = 'array';
        }

        return parent::getCasts();
    }

    /**
     * Добавить значения для флага.
     *
     * @param  string  $name  Название значения.
     * @param  mixed  $value  Значения.
     *
     * @return Flags Вернет текущую модель.
     */
    protected function addFlagValue(string $name, mixed $value): static
    {
        $this->setAttribute("flags->$name", $value);

        return $this;
    }

    /**
     * Добавление значения для флага.
     *
     * @param  string  $name  Название значения.
     * @param  mixed  $value  Значения.
     *
     * @return Flags Вернет текущую модель.
     */
    public function addFlag(string $name, mixed $value): static
    {
        $this->addFlagValue($name, $value);

        return $this;
    }

    /**
     * Удаление значения для флага.
     *
     * @param  string  $name  Название значения.
     *
     * @return Flags Вернет текущую модель.
     */
    public function removeFlag(string $name): static
    {
        $this->addFlagValue($name, null);

        return $this;
    }

    /**
     * Получения значения для флага.
     *
     * @param  string  $name  Название значения.
     *
     * @return mixed Значение для флага.
     */
    public function getFlag(string $name): mixed
    {
        return $this->flags[$name] ?? null;
    }

    /**
     * Проверка существует ли флаг.
     *
     * @param  string  $name  Название значения.
     *
     * @return bool Вернет результат проверки.
     */
    public function checkFlag(string $name): bool
    {
        return (bool)($this->flags[$name] ?? false);
    }

    /**
     * Массовая установка флагов.
     *
     * @param  array  $value  Массив флагов.
     *
     * @return Flags Вернет текущую модель.
     */
    public function setFlags(array $value): static
    {
        $this->attributes['flags'] = $this->asJson($value);

        return $this;
    }
}
