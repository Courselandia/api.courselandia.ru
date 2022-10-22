<?php
/**
 * Отправка SMS.
 * Пакет содержит классы для отправки SMS с сайта.
 *
 * @package App.Models.Sms
 */

namespace App\Models\Sms;

use Log;
use App\Models\Contracts\Sms;

/**
 * Классы драйвер для отправки СМС сообщений с сайта с записью в логи.
 */
class SmsLog extends Sms
{
    /**
     * Отправка СМС сообщения.
     *
     * @param  string  $phone  Номер телефона.
     * @param  string  $message  Сообщение для отправки.
     * @param  string  $sender  Отправитель.
     * @param  bool  $isTranslit  Если указать true, то нужно транслитерировать сообщение в латиницу.
     *
     * @return string|null Вернет идентификатор сообщения, если сообщение было отправлено.
     */
    public function send(string $phone, string $message, string $sender, bool $isTranslit = false): ?string
    {
        $sender = str_replace(['+', '-', ''], '', $sender);

        Log::notice('Send SMS', [
            'module' => 'SMS',
            'to' => $phone,
            'sender' => $sender,
            'message' => $message
        ]);

        return null;
    }

    /**
     * Метод проверки статуса отправки сообщения.
     *
     * @param  string  $index  Индекс отправленного сообщения.
     * @param  string  $phone  Номер телефона.
     *
     * @return bool Вернет true, если сообщение было отправлено.
     */
    public function check(string $index, string $phone): bool
    {
        return true;
    }
}
