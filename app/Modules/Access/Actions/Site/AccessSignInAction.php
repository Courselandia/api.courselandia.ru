<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Modules\Access\Decorators\Site\AccessSignInDecorator;
use App\Modules\Access\Entities\AccessSignedIn;
use App\Modules\Access\Pipes\Site\SignIn\LoginPipe;
use App\Modules\Access\Pipes\Site\SignIn\GatePipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;
use App\Modules\Access\Pipes\Site\SignIn\DataPipe;

/**
 * Регистрация нового пользователя.
 */
class AccessSignInAction extends Action
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
     * Метод запуска логики.
     *
     * @return AccessSignedIn Вернет результаты исполнения.
     */
    public function run(): AccessSignedIn
    {
        $decorator = app(AccessSignInDecorator::class);
        $decorator->login = $this->login;
        $decorator->password = $this->password;
        $decorator->remember = $this->remember;

        return $decorator->setActions([
            LoginPipe::class,
            GatePipe::class,
            AuthPipe::class,
            DataPipe::class
        ])->run();
    }
}
