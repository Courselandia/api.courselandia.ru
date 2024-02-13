<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Exception;

/**
 * Трейт ошибок.
 */
trait Error
{
    /**
     * Массив ошибок.
     *
     * @var Exception[]
     */
    public array $errors = [];

    /**
     * Очистить ошибки.
     *
     * @return Error
     */
    public function cleanErrors(): static
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
        return count($this->errors) !== 0;
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
     * @param Exception|string $error Ошибка.
     *
     * @return Error
     */
    public function addError(Exception|string $error): static
    {
        $this->errors[] = is_string($error) ? new Exception($error) : $error;

        return $this;
    }

    /**
     * Получение ошибки по номеру.
     *
     * @param int $index Номер ошибки.
     *
     * @return Exception|null Вернет исключение.
     */
    public function getError(int $index = 0): ?Exception
    {
        if ($this->hasError() && isset($this->errors[$index])) {
            return $this->errors[$index];
        }

        return null;
    }

    /**
     * Получение всех ошибок.
     *
     * @return Exception[] Вернет массив всех ошибок с исключениями.
     */
    public function getErrors(): array
    {
        if ($this->hasError()) {
            return $this->errors;
        }

        return [];
    }

    /**
     * Установка всех ошибок.
     *
     * @param Exception[] $errors Массив ошибок.
     *
     * @return Error
     */
    public function setErrors(array $errors): static
    {
        $this->errors = $errors;

        return $this;
    }
}
