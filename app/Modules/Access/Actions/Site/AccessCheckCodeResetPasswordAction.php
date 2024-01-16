<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use Util;
use Cache;
use Config;
use App\Models\Action;
use App\Modules\User\Models\User;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidCodeException;
use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;

/**
 * Проверка кода на изменение пароля пользователя.
 */
class AccessCheckCodeResetPasswordAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Код восстановления пользователя.
     *
     * @var string
     */
    private string $code;

    /**
     * @param int|string $id ID пользователя.
     * @param string $code Код восстановления пользователя.
     */
    public function __construct(int|string $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws InvalidCodeException
     * @throws UserNotExistException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('access', 'user', 'recovery', $this->id);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $user = User::where('id', $this->id)
                    ->active()
                    ->with([
                        'recovery'
                    ])->first();

                if ($user) {
                    return UserEntity::from($user->toArray());
                }

                return null;
            }
        );

        if ($user) {
            if ($user->recovery?->code === $this->code || Config::get('app.env') === 'testing' || Config::get(
                    'app.env'
                ) === 'local') {
                return true;
            }

            throw new InvalidCodeException(
                trans('access::actions.site.accessCheckCodeResetPasswordAction.codeNotCorrect')
            );
        }

        throw new UserNotExistException(trans('access::actions.site.accessCheckCodeResetPasswordAction.notExistUser'));
    }
}
