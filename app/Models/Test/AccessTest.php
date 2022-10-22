<?php
/**
 * Тестирование.
 * Пакет содержит классы для выполнения стандартных процедур тестирования.
 *
 * @package App.Models.Test
 */

namespace App\Models\Test;

/**
 * Класс для получения логина и пароля для валидации.
 */
trait AccessTest
{
    /**
     * Получение токена аутентификации для администратора.
     *
     * @param  string  $type  Тип.
     *
     * @return string Вернет данные.
     */
    public function getAdmin(string $type): string
    {
        $credential = [
            'login' => 'admin@courselandia.ru',
            'password' => 'admin'
        ];

        return $credential[$type];
    }

    /**
     * Получение токена аутентификации для пользователя.
     *
     * @param  string  $type  Тип.
     *
     * @return string Вернет данные.
     */
    public function getUser(string $type): string
    {
        $credential = [
            'login' => 'user@courselandia.ru',
            'password' => 'user'
        ];

        return $credential[$type];
    }

    /**
     * Получение токена аутентификации для менеджера.
     *
     * @param  string  $type  Тип.
     *
     * @return string Вернет данные.
     */
    public function getManager(string $type): string
    {
        $credential = [
            'login' => 'manager@courselandia.ru',
            'password' => 'manager'
        ];

        return $credential[$type];
    }

    /**
     * Получение токена аутентификации для не валидированного пользователя.
     *
     * @param  string  $type  Тип.
     *
     * @return string Вернет данные.
     */
    public function getUnverified(string $type): string
    {
        $credential = [
            'login' => 'unverified@courselandia.ru',
            'password' => 'unverified'
        ];

        return $credential[$type];
    }

    /**
     * Получение токена аутентификации для не существующего пользователя.
     *
     * @param  string  $type  Тип.
     *
     * @return string Вернет данные.
     */
    public function getUnknownUser(string $type): string
    {
        $credential = [
            'login' => 'unknown-user@courselandia.ru',
            'password' => 'unknown-user'
        ];

        return $credential[$type];
    }
}
