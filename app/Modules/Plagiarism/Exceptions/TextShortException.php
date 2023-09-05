<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Exceptions;

use Exception;

/**
 * Исключение при ошибки запроса к сервису если проверяемый текст слишком коротки.
 */
class TextShortException extends Exception
{

}
