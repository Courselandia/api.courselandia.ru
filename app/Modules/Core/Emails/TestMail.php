<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Emails;

use Illuminate\Mail\Mailable;

/**
 * Класс сообщения для отправки email для тестирования почты.
 */
class TestMail extends Mailable
{
    /**
     * Построитель сообщения.
     *
     * @return Mailable Вернет объект письма.
     */
    public function build(): Mailable
    {
        return $this->subject('Test mail')->view('core::site.mail');
    }
}
