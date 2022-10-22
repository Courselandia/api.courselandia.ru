<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Абстрактный класс для создания действия.
 */
abstract class Action
{
    use Event;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    abstract public function run(): mixed;
}
