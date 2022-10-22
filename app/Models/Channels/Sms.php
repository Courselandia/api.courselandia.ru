<?php
/**
 * Каналы.
 * Этот пакет содержит каналы для быстрой отправки сообщений.
 *
 * @package App.Models.Channels
 */

namespace App\Models\Channels;

use Illuminate\Notifications\Notification;
use Sms as SmsFacade;

/**
 * Канал отправки SMS сообщений.
 */
class Sms
{
    /**
     * Отправка сообщения.
     *
     * @param  mixed  $notifiable
     * @param  Notification  $notification
     *
     * @return SmsFacade;
     */
    public function send(mixed $notifiable, Notification $notification): SmsFacade
    {
        $message = $notification->toSms($notifiable);

        return SmsFacade::send(
            $notifiable->routeNotificationForPhone(),
            $message->message,
            $message->sender,
            $message->translit
        );
    }
}
