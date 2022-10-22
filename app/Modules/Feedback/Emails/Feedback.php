<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Emails;

use Util;
use Config;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Класс сообщения для отправки email с формы обратной связи.
 */
class Feedback extends Mailable
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
     * Имя.
     *
     * @var string
     */
    private string $name;

    /**
     * E-mail.
     *
     * @var string
     */
    private string $email;

    /**
     * Телефон.
     *
     * @var string
     */
    private string $phone;

    /**
     * Сообщение.
     *
     * @var string
     */
    private string $msg;

    /**
     * Конструктор.
     *
     * @param  string  $name  Имя.
     * @param  string  $email  E-mail.
     * @param  string  $phone  Телефон.
     * @param  string  $msg  Сообщение.
     */
    public function __construct(string $name, string $email, string $phone, string $msg)
    {
        $this->site = Config::get('app.url');
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->msg = Util::getText($msg);
        $this->site = Config::get('app.url');
    }

    /**
     * Построитель сообщения.
     *
     * @return Mailable Вернет объект письма.
     */
    public function build(): Mailable
    {
        return $this->subject('Feedback from an user')->view('feedback::site.mail', [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'msg' => $this->msg,
            'site' => $this->site,
        ]);
    }
}
