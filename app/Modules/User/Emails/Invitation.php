<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аунтификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Emails;

use Config;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Класс для отправки приглашения.
 */
class Invitation extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * URL сайта.
     *
     * @var string
     */
    private string $site;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->site = Config::get('app.url');
    }

    /**
     * Построитель сообщения.
     *
     * @return Mailable Вернет объект письма.
     */
    public function build(): Mailable
    {
        return $this
            ->subject(trans('user::email.invitation.title'))
            ->view('user::site.invitation', [
                'site' => $this->site,
            ]);
    }
}
