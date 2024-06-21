<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Log;

/**
 * Измерение скорости кода.
 */
class Speed
{
    public static function timer($name, callable $function): mixed
    {
        $start = microtime(true);
        $result = $function();

        $diff = sprintf('%.6f сек.', microtime(true) - $start);

        Log::debug('Измерение скорости ' . $name . ': ' . $diff);

        return $result;
    }
}