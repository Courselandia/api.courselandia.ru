<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Exception;
use JetBrains\PhpStorm\Pure;

/**
 * Трейт ошибок.
 */
trait Error
{
    /**
     * Массив ошибок.
     *
     * @var array
     */
    private array $errors = [];

    /**
     * Очистить ошибку.
     *
     * @return Error
     */
    public function cleanError(): static
    {
        $this->errors = [];

        return $this;
    }

    /**
     * Проверка наличия ошибки.
     *
     * @return bool Вернет true если есть ошибка.
     */
    public function hasError(): bool
    {
        return count($this->errors) === 0;
    }

    /**
     * Выброс ошибки.
     *
     * @return Error
     * @throws Exception
     */
    public function throwError(): static
    {
        if ($this->hasError()) {
            throw $this->errors[0];
        }

        return $this;
    }

    /**
     * Добавление ошибки.
     *
     * @param  Exception  $error  Ошибка.
     *
     * @return Error
     */
    public function addError(Exception $error): static
    {
        $this->errors[] = $error;

        return $this;
    }

    /**
     * Получение ошибки по номеру.
     *
     * @param  int  $index  Номер ошибки.
     *
     * @return Exception|null Вернет исключение.
     */
    #[Pure] public function getError(int $index = 0): ?Exception
    {
        if ($this->hasError() && isset($this->errors[$index])) {
            return $this->errors[$index];
        }

        return null;
    }

    /**
     * Получение всех ошибок.
     *
     * @return array Вернет массив всех ошибок с исключениями.
     *

     */
    #[Pure] public function getErrors(): array
    {
        if ($this->hasError()) {
            return $this->errors;
        }

        return [];
    }

    /**
     * Установка всех ошибок.
     *
     * @param  array  $errors  Массив ошибок.
     *
     * @return Error
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }
}
