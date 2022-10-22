<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Entities\AccessSignedIn;
use App\Modules\Access\Entities\AccessSignIn;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для авторизации пользователя.
 */
class AccessSignInDecorator extends Decorator
{
    /**
     * Логин пользователя.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSignedIn Вернет данные авторизации.
     */
    public function run(): AccessSignedIn
    {
        $accessSignIn = new AccessSignIn();
        $accessSignIn->login = $this->login;
        $accessSignIn->password = $this->password;
        $accessSignIn->remember = $this->remember;

        return app(Pipeline::class)
            ->send($accessSignIn)
            ->through($this->getActions())
            ->thenReturn();
    }
}
