<?php
/**
 * Тестирование.
 * Пакет содержит классы для выполнения стандартных процедур тестирования.
 *
 * @package App.Models.Test
 */

namespace App\Models\Test;

/**
 * Классы для получения токена.
 */
trait TokenTest
{
    use AccessTest;

    /**
     * Получение токена аутентификации для администратора.
     *
     * @return string|null Вернет токен.
     */
    public function getAdminToken(): ?string
    {
        $token = $this->json('POST', 'api/token', [
            'login' => $this->getAdmin('login'),
            'password' => $this->getAdmin('password'),
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }

    /**
     * Получение токена аутентификации для пользователя.
     *
     * @return string|null Вернет токен.
     */
    public function getUserToken(): ?string
    {
        $token = $this->json('POST', 'api/token', [
            'login' => $this->getUser('login'),
            'password' => $this->getUser('password'),
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }

    /**
     * Получение токена аутентификации для менеджера.
     *
     * @return string|null Вернет токен.
     */
    public function getManagerToken(): ?string
    {
        $token = $this->json('POST', 'api/token', [
            'login' => $this->getManager('login'),
            'password' => $this->getManager('password'),
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }

    /**
     * Получение токена аутентификации для не валидированного пользователя.
     *
     * @return string|null Вернет токен.
     */
    public function getUnverifiedToken(): ?string
    {
        $token = $this->json('POST', 'api/token', [
            'login' => $this->getUnverified('login'),
            'password' => $this->getUnverified('password'),
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }

    /**
     * Получение токена аутентификации.
     *
     * @param  string  $login  Логин.
     * @param  string  $password  Пароль.
     *
     * @return string|null Вернет токен.
     */
    public function getToken(string $login, string $password): ?string
    {
        $token = $this->json('POST', 'api/token', [
            'login' => $login,
            'password' => $password,
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }
}
