<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use App\Models\Contracts\Pipe;

/**
 * Класс для создания собственного декоратора
 */
abstract class Decorator
{
    /**
     * Массив действий.
     *
     * @var array
     */
    private array $actions = [];

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return mixed Вернет результат действия.
     */
    abstract public function run(): mixed;

    /**
     * Добавление действий.
     *
     * @param  array  $actions  Массив действий.
     *
     * @return Decorator Возвращает текущий объект.
     */
    public function setActions(array $actions): Decorator
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * Добавляем одно действие для установки.
     *
     * @param  Pipe  $action  Действие для установки.
     *
     * @return Decorator Возвращает текущий объект.
     */
    public function addAction(Pipe $action): Decorator
    {
        $this->actions[] = $action;

        return $this;
    }

    /**
     * Возвращает все действия.
     *
     * @return array Вернет массив всех действий.
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}

