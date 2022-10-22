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
 * Исключение если проверочный код неверен.
 */
class InvalidCodeException extends Exception
{

}
