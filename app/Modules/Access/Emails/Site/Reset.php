<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Emails\Site;

use Config;
use App\Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Класс для отправки email после смены пароля.
 */
class Reset extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Сущность пользователя.
     *
     * @var User
     */
    private User $user;

    /**
     * URL сайта.
     *
     * @var string
     */
    private string $site;

    /**
     * Конструктор.
     *
     * @param  User  $user  Сущность пользователя.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->site = Config::get('app.url');
    }

    /**
     * Построитель сообщения.
     *
     * @return Mailable Вернет объект письма.
     */
    public function build(): Mailable
    {
        return $this->subject(trans('access::emails.site.reset.title'))->view('access::reset', [
            'user' => $this->user,
            'site' => $this->site,
        ]);
    }
}
