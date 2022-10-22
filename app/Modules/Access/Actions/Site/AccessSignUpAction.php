<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Modules\Access\Decorators\Site\AccessSignUpDecorator;
use App\Modules\Access\Entities\AccessSignedUp;
use App\Modules\Access\Pipes\Site\SignUp\CreatePipe;
use App\Modules\Access\Pipes\Site\SignUp\RolePipe;
use App\Modules\Access\Pipes\Site\SignUp\VerificationPipe;
use App\Modules\Access\Pipes\Gate\GetPipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;
use App\Modules\Access\Pipes\Site\SignUp\DataPipe;

/**
 * Регистрация нового пользователя.
 */
class AccessSignUpAction extends Action
{
    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public ?string $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Верифицировать пользователя.
     *
     * @var bool
     */
    public bool $verify = false;

    /**
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public ?string $uid = null;

    /**
     * Метод запуска логики.
     *
     * @return AccessSignedUp Вернет результаты исполнения.
     */
    public function run(): AccessSignedUp
    {
        $decorator = app(AccessSignUpDecorator::class);

        $decorator->login = $this->login;
        $decorator->password = $this->password;
        $decorator->first_name = $this->first_name;
        $decorator->second_name = $this->second_name;
        $decorator->phone = $this->phone;
        $decorator->verify = $this->verify;
        $decorator->uid = $this->uid;
        $decorator->create = true;

        return $decorator->setActions([
            CreatePipe::class,
            RolePipe::class,
            VerificationPipe::class,
            GetPipe::class,
            AuthPipe::class,
            DataPipe::class
        ])->run();
    }
}
