<?php
/**
 * Исключения ядра.
 * Этот пакет содержит исключения ядра системы.
 *
 * @package App.Models.Exception
 */

namespace App\Models\Exceptions;

use Exception;
use Throwable;
use JetBrains\PhpStorm\Pure;

/**
 * Исключение при валидации.
 */
class ValidateException extends Exception
{
    /**
     * Сущность в которой произошла ошибка.
     *
     * @var string|null
     */
    protected ?string $_entity;

    /**
     * Конструктор.
     *
     * @param  null  $message  Сообщение об ошибки.
     * @param  null  $entity  Сущность в которой произошла ошибка.
     * @param  null  $code  Код ошибки.
     * @param  Throwable|null  $previous  Предыдущая ошибка использованная для привязке к цепи ошибок.
     */
    #[Pure] public function __construct($message = null, $entity = null, $code = null, Throwable $previous = null)
    {
        $this->_entity = $entity;

        parent::__construct($message, $code, $previous);
    }

    /**
     * Получения сущности в которой произошла ошибка.
     *
     * @return string|null Сущность.
     */
    public function getEntity(): ?string
    {
        return $this->_entity;
    }
}
