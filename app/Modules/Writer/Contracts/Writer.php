<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Contracts;

/**
 * Абстрактный класс для создания собственного драйвера для написания текстов.
 */
abstract class Writer
{
    /**
     * Запрос на написание текста.
     *
     * @param string $request Запрос на написания текста.
     * @param array|null $options Дополнительные опции настройки сети.
     * @return string ID задачи на генерацию.
     */
    abstract public function request(string $request, array $options = null): string;

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     * @return string Готовый текст.
     */
    abstract public function result(string $id): string;
}
