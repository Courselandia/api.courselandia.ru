<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Modules\Access\Decorators\Site\AccessSocialDecorator;
use App\Modules\Access\DTO\Actions\AccessSocial as AccessSocialDto;
use App\Modules\Access\DTO\Decorators\AccessSocial as AccessSocialDTODecorator;
use App\Modules\Access\Entities\AccessSocial as AccessSocialEntity;
use App\Modules\Access\Pipes\Gate\GetPipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;
use App\Modules\Access\Pipes\Site\SignUp\CreatePipe;
use App\Modules\Access\Pipes\Site\SignUp\RolePipe;
use App\Modules\Access\Pipes\Site\SignUp\VerificationPipe;
use App\Modules\Access\Pipes\Site\Social\CheckPipe;
use App\Modules\Access\Pipes\Site\Social\DataPipe;
use App\Modules\Access\Pipes\Site\Social\TokenPipe;

/**
 * Регистрация нового пользователя через социальные сети.
 */
class AccessSocialAction extends Action
{
    /**
     * DTO для действия регистрации или входа через социальную сеть.
     *
     * @var AccessSocialDto
     */
    private AccessSocialDto $data;

    public function __construct(AccessSocialDto $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return AccessSocialEntity Сущность для зарегистрированного пользователя.
     */
    public function run(): AccessSocialEntity
    {
        $decorator = new AccessSocialDecorator(AccessSocialDTODecorator::from($this->data->toArray()));

        return $decorator->setActions([
            CheckPipe::class,
            TokenPipe::class,
            DataPipe::class,
            CreatePipe::class,
            RolePipe::class,
            VerificationPipe::class,
            GetPipe::class,
            AuthPipe::class,
        ])->run();
    }
}
