<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\UserConfig;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Models\User;
use Cache;
use Util;

/**
 * Получение конфигураций пользователя.
 */
class UserConfigGetAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string
     */
    private int|string $id;

    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws UserNotExistException
     */
    public function run(): array
    {
        if ($this->id) {
            $cacheKey = Util::getKey('user', 'model', $this->id);

            $user = Cache::tags(['user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return User::find($this->id);
                }
            );

            if ($user) {
                $cacheKey = Util::getKey('user', 'flags', $this->id);

                return Cache::tags(['user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($user) {
                        return $user->flags ?: [];
                    }
                );
            }
        }

        throw new UserNotExistException(trans('user::actions.admin.userConfigUpdateAction.notExistUser'));
    }
}
