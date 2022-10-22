<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Emails\Site;

use App\Modules\User\Entities\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Config;

/**
 * Класс для отправки верификации.
 */
class Verification extends Mailable
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
     * Код восстановления.
     *
     * @var string
     */
    private string $code;

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
     * @param  string  $code  Код восстановления пользователя.
     */
    public function __construct(User $user, string $code)
    {
        $this->user = $user;
        $this->code = $code;
        $this->site = Config::get('app.url');
    }

    /**
     * Построитель сообщения.
     *
     * @return Mailable Вернет объект письма.
     */
    public function build(): Mailable
    {
        return $this->subject(trans('access::emails.site.verification.title'))->view(
            'access::verification',
            [
                'user' => $this->user,
                'code' => $this->code,
                'site' => $this->site,
            ]
        );
    }
}
