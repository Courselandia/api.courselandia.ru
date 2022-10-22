<?php
/**
 * Отправка SMS.
 * Пакет содержит классы для отправки SMS с сайта.
 *
 * @package App.Models.Sms
 */

namespace App\Models\Sms;

use Config;
use Illuminate\Support\Manager;

/**
 * Класс системы отправки СМС сообщений.
 */
class SmsManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return Config::get('sms.driver');
    }
}
