<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use Cache;
use Exception;
use Mail;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRecovery;
use App\Modules\Access\Emails\Site\Recovery;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\UserRecovery as UserRecoveryEntity;

/**
 * Отправка e-mail для восстановления пароля.
 */
class AccessForgetAction extends Action
{
    /**
     * Логин пользователя.
     *
     * @var string
     */
    private string $login;

    /**
     * @param string|null $login Логин пользователя.
     */
    public function __construct(string $login = null)
    {
        $this->login = $login;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws Exception
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('access', 'user', $this->login);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $user = User::where('login', $this->login)
                    ->active()
                    ->first();

                if ($user) {
                    return UserEntity::from($user->toArray());
                }

                return null;
            }
        );

        if ($user) {
            $code = UserRecoveryEntity::generateCode();

            $cacheKey = Util::getKey('access', 'userRecovery', 'model', 'user_id', $user->id);

            $recovery = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($user) {
                    return UserRecovery::where('user_id', $user->id)->first();
                }
            );

            if ($recovery) {
                $recovery->code = $code;
                $recovery->save();
            } else {
                UserRecovery::create([
                    'user_id' => $user->id,
                    'code' => $code,
                ]);
            }

            Cache::tags(['access', 'user'])->flush();

            Mail::to($user->login)->queue(new Recovery($user, $code));

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessForgetAction.notExistUser'));
    }
}
