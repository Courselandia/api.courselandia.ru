<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Exception;
use Mail;
use App\Models\Action;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRecovery;
use App\Modules\Access\Emails\Site\Recovery;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\UserRecovery as UserRecoveryEntity;
use Util;

/**
 * Отправка e-mail для восстановления пароля.
 */
class AccessForgetAction extends Action
{
    /**
     * Логин пользователя.
     *
     * @var string|null
     */
    public ?string $login = null;

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
                    return new UserEntity($user->toArray());
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
                $recovery = new UserRecoveryEntity();
                $recovery->user_id = $user->id;
                $recovery->code = $code;

                UserRecovery::create($recovery->toArray());
            }

            Cache::tags(['access', 'user'])->flush();

            Mail::to($user->login)->queue(new Recovery($user, $code));

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessForgetAction.notExistUser'));
    }
}
