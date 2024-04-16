<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Action;
use App\Models\Exceptions\UserVerifiedException;
use App\Modules\User\Models\User;
use Cache;
use Util;

/**
 * Отправка e-mail сообщения пользователю для верификации.
 */
class AccessSendEmailVerificationAction extends Action
{
    /**
     * Логин пользователя.
     *
     * @var string
     */
    private string $login;

    /**
     * @param string $login Логин пользователя.
     */
    public function __construct(string $login)
    {
        $this->login = $login;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws UserVerifiedException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('access', 'user', 'model', $this->login);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return User::where('login', $this->login)
                    ->active()
                    ->first();
            }
        );

        if ($user) {
            $action = new AccessSendEmailVerificationCodeAction($user->id);
            $action->run();

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessSendEmailVerificationAction.notExistUser'));
    }
}
