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
use App\Modules\Access\DTO\Actions\AccessSignIn;
use App\Modules\Access\DTO\Decorators\AccessSignIn as AccessSignInDtoDecorator;
use App\Modules\Access\Entities\AccessSignedIn as AccessSignedInEntity;
use App\Modules\Access\Pipes\Site\SignIn\LoginPipe;
use App\Modules\Access\Pipes\Site\SignIn\GatePipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;

/**
 * Регистрация нового пользователя.
 */
class AccessSignInAction extends Action
{
    private AccessSignIn $data;

    public function __construct(AccessSignIn $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return AccessSignedInEntity Сущность для авторизованного пользователя.
     */
    public function run(): AccessSignedInEntity
    {
        $decorator = new AccessSignInDecorator(AccessSignInDtoDecorator::from($this->data->toArray()));

        return $decorator->setActions([
            LoginPipe::class,
            GatePipe::class,
            AuthPipe::class,
        ])->run();
    }
}
