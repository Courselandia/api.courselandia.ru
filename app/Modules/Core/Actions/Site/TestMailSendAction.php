<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Actions\Site;

use Mail;
use App\Models\Action;
use App\Modules\Core\Emails\TestMail;

/**
 * Класс для отправки тестового сообщения.
 */
class TestMailSendAction extends Action
{
    /**
     * E-mail.
     *
     * @var string
     */
    private string $email;

    /**
     * @param string $email E-mail.
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        Mail::to($this->email)->send(new TestMail());

        return true;
    }
}
