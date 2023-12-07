<?php
/**
 * Модуль индексации страниц.
 * Этот модуль содержит все классы для системы индексации страниц поисковыми системами.
 *
 * @package App\Modules\Crawl
 */

namespace App\Modules\Crawl\Engines\Tokens;

/**
 * Токен для Yandex.
 */
class YandexToken
{
    /**
     * ID пользователя.
     *
     * @var int
     */
    private int $userId;

    /**
     * Метка сайта.
     *
     * @var string
     */
    private string $host;

    /**
     * Конструктор.
     *
     * @param int $userId ID пользователя.
     * @param string $host Метка сайта.
     */
    public function __construct(int $userId, string $host)
    {
        $this->userId = $userId;
        $this->host = $host;
    }

    /**
     * Получение ID пользователя.
     *
     * @return int ID пользователя.
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * Получение метка сайта.
     *
     * @return string Метка сайта.
     */
    public function getHost(): string
    {
        return $this->host;
    }
}
