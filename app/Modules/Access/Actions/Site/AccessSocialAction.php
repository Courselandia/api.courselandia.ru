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
use App\Modules\Access\Pipes\Gate\GetPipe;
use App\Modules\Access\Pipes\Site\SignIn\AuthPipe;
use App\Modules\Access\Pipes\Site\SignUp\RolePipe;
use App\Modules\Access\Pipes\Site\SignUp\VerificationPipe;
use App\Modules\Access\Pipes\Site\Social\CheckPipe;
use App\Modules\Access\Pipes\Site\Social\ClientPipe;
use App\Modules\Access\Pipes\Site\Social\DataPipe;
use App\Modules\Access\Pipes\Site\SignUp\DataPipe as DataPipeSignUp;
use App\Modules\Access\Pipes\Site\SignUp\CreatePipe;
use App\Modules\User\Enums\Role;

use App\Models\Enums\EnumList;

/**
 * Регистрация нового пользователя через социальные сети.
 */
class AccessSocialAction extends Action
{
    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

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
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified = false;

    /**
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public ?string $uid = null;

    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Название социальной сети.
     *
     * @var string|null
     */
    public ?string $social = null;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     */
    public function run(): mixed
    {
        $decorator = app(AccessSocialDecorator::class);
        $decorator->login = $this->login;
        $decorator->first_name = $this->first_name;
        $decorator->second_name = $this->second_name;
        $decorator->verified = $this->verified;
        $decorator->social = $this->social;
        $decorator->uid = $this->uid;

        return $decorator->setActions([
            CheckPipe::class,
            ClientPipe::class,
            DataPipe::class,
            CreatePipe::class,
            RolePipe::class,
            VerificationPipe::class,
            GetPipe::class,
            AuthPipe::class,
            DataPipeSignUp::class,
        ])->run();
    }
}
