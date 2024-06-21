<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

/**
 * Измерение скорости кода.
 */
class Speed
{
    public static function timer($name, callable $function): mixed
    {
        $result = $function();

        return $result;
    }
}