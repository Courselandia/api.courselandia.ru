<?php
/**
 * Исключения ядра.
 * Этот пакет содержит исключения ядра системы.
 *
 * @package App.Models.Exception
 */

namespace App\Models\Exceptions;

use Exception;

/**
 * Исключение в случае если сервис занят.
 */
class LimitException extends Exception
{

}
