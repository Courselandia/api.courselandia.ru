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
        $client = $this->json('POST', 'api/client', [
            'login' => $this->getAdmin('login'),
            'password' => $this->getAdmin('password')
        ])->getContent();

        $client = json_decode($client, true);

        $token = $this->json('POST', 'api/token', [
            'secret' => $client['data']['secret']
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
        $client = $this->json('POST', 'api/client', [
            'login' => $this->getUser('login'),
            'password' => $this->getUser('password')
        ])->getContent();

        $client = json_decode($client, true);

        $token = $this->json('POST', 'api/token', [
            'secret' => $client['data']['secret']
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
        $client = $this->json('POST', 'api/client', [
            'login' => $this->getManager('login'),
            'password' => $this->getManager('password')
        ])->getContent();

        $client = json_decode($client, true);

        $token = $this->json('POST', 'api/token', [
            'secret' => $client['data']['secret']
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
        $client = $this->json('POST', 'api/client', [
            'login' => $this->getUnverified('login'),
            'password' => $this->getUnverified('password')
        ])->getContent();

        $client = json_decode($client, true);

        $token = $this->json('POST', 'api/token', [
            'secret' => $client['data']['secret']
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
        $client = $this->json('POST', 'api/client', [
            'login' => $login,
            'password' => $password
        ])->getContent();

        $client = json_decode($client, true);

        $token = $this->json('POST', 'api/token', [
            'secret' => $client['data']['secret']
        ])->getContent();

        $token = json_decode($token, true);

        return $token['data']['accessToken'];
    }
}
