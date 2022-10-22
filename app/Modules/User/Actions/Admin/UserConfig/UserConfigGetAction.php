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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use Cache;
use ReflectionException;
use Util;

/**
 * Получение конфигураций пользователя.
 */
class UserConfigGetAction extends Action
{
    /**
     * Репозиторий для выбранных групп пользователя.
     *
     * @var User
     */
    private User $user;

    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws UserNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): array
    {
        if ($this->id) {
            $query = new RepositoryQueryBuilder($this->id);

            $cacheKey = Util::getKey('user', $query);

            $user = Cache::tags(['user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->user->get($query);
                }
            );

            if ($user) {
                $cacheKey = Util::getKey('user', 'flags', $query);

                return Cache::tags(['user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($query) {
                        return $this->user->flags($query);
                    }
                );
            }
        }

        throw new UserNotExistException(trans('user::actions.admin.userConfigUpdateAction.notExistUser'));
    }
}
