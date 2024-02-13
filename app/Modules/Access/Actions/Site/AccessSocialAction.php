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
use App\Modules\Access\Data\Actions\AccessSocial as AccessSocialData;
use App\Modules\Access\Data\Decorators\AccessSocial as AccessSocialDataDecorator;
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
     * Данные для действия регистрации или входа через социальную сеть.
     *
     * @var AccessSocialData
     */
    private AccessSocialData $data;

    public function __construct(AccessSocialData $data)
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
        $decorator = new AccessSocialDecorator(AccessSocialDataDecorator::from($this->data->toArray()));

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
