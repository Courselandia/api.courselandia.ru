<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use Util;

/**
 * Получение пользователя.
 */
class UserGetAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return UserEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?UserEntity
    {
        if ($this->id) {
            $cacheKey = Util::getKey('user', $this->id, 'verification', 'role');

            return Cache::tags(['user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    $user = User::where('id', $this->id)
                        ->with([
                            'verification',
                            'role',
                        ])->first();

                    return $user ? new UserEntity($user->toArray()) : null;
                }
            );
        }

        return null;
    }
}
