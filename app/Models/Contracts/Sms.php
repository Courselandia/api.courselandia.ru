<?php
/**
 * Контракты.
 * Этот пакет содержит контракты ядра системы.
 *
 * @package App.Models.Contracts
 */

namespace App\Models\Contracts;

/**
 * Абстрактный класс позволяющий проектировать собственные классы для отправки СМС сообщений с сайта.
 */
abstract class Sms
{
    /**
     * Абстрактный метод отправки СМС сообщения.
     *
     * @param  string  $phone  Номер телефона.
     * @param  string  $message  Сообщение для отправки.
     * @param  string  $sender  Отправитель.
     * @param  bool  $isTranslit  Если указать true, то нужно транслитерировать сообщение в латиницу.
     *
     * @return string|null Вернет идентификатор сообщения, если сообщение было отправлено.
     */
    abstract public function send(string $phone, string $message, string $sender, bool $isTranslit = false): ?string;

    /**
     * Абстрактный метод проверки статуса отправки сообщения.
     *
     * @param  string  $index  Индекс отправленного сообщения.
     * @param  string  $phone  Номер телефона.
     *
     * @return bool Вернет true, если сообщение было отправлено.
     */
    abstract public function check(string $index, string $phone): bool;
}
