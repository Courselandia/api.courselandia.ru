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
use App\Modules\Access\DTO\Actions\AccessSignUp;
use App\Modules\Access\Entities\AccessSignedUp;
use App\Modules\Access\Pipes\Site\SignUp\CreatePipe;
use App\Modules\Access\Pipes\Site\SignUp\RolePipe;
use App\Modules\Access\Pipes\Site\SignUp\VerificationPipe;
use App\Modules\Access\Pipes\Gate\GetPipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;
use App\Modules\Access\DTO\Decorators\AccessSignUp as AccessSignUpDtoDecorator;

/**
 * Регистрация нового пользователя.
 */
class AccessSignUpAction extends Action
{
    /**
     * DTO для действия регистрация нового пользователя.
     *
     * @var AccessSignUp
     */
    private AccessSignUp $data;

    public function __construct(AccessSignUp $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return AccessSignedUp Сущность для зарегистрированного пользователя.
     */
    public function run(): AccessSignedUp
    {
        $decorator = new AccessSignUpDecorator(AccessSignUpDtoDecorator::from($this->data->toArray()));

        return $decorator->setActions([
            CreatePipe::class,
            RolePipe::class,
            VerificationPipe::class,
            GetPipe::class,
            AuthPipe::class,
        ])->run();
    }
}
