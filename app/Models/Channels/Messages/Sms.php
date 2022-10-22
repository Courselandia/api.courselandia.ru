<?php
/**
 * Сборка сообщений.
 * Этот пакет содержит классы для сборки быстрых сообщений.
 *
 * @package App.Models.Channels
 */

namespace App\Models\Channels\Messages;

/**
 * Класс сборки SMS сообщений для быстрой отправки.
 */
class Sms
{
    /**
     * Уровень сообщения.
     *
     * @var string
     */
    public string $level = 'info';

    /**
     * Отправитель сообщения.
     *
     * @var string
     */
    public string $sender;

    /**
     * Сообщение для отправки.
     *
     * @var string
     */
    public string $message;

    /**
     * Булево значение, определяющее нужно ли транслировать текст.
     *
     * @var bool
     */
    public bool $translit = false;

    /**
     * Индикатор того, что сообщение было удачно отправлено.
     *
     * @return Sms
     */
    public function success(): Sms
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * Индикатор того что во время отправки произошла ошибка.
     *
     * @return Sms
     */
    public function error(): Sms
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * Устанавливаем отправителя сообщения.
     *
     * @param  string  $sender  Отправитель сообщения.
     *
     * @return Sms
     */
    public function sender(string $sender): Sms
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Устанавливаем сообщение на отправку.
     *
     * @param  string  $message  Сообщение для отправки.
     *
     * @return Sms
     */
    public function message(string $message): Sms
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Определяем нужно ли транслировать текст.
     *
     * @param  bool  $translit  Булево значение для определения транслитерации.
     *
     * @return Sms
     */
    public function translit(bool $translit): Sms
    {
        $this->translit = $translit;

        return $this;
    }
}
