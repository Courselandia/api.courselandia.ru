<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Models\User;

/**
 * Получение всех доступов к разделам.
 */
class AccessGateAction extends Action
{
    /**
     * ID пользователя.
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
        $key = Util::getKey('access', 'gate', $this->id);

        return Cache::tags(['access', 'user'])->remember(
            $key,
            CacheTime::MONTH->value,
            function () {
                $user = User::where('id', $this->id)
                    ->active()
                    ->with([
                        'verification',
                        'role',
                    ])->first();

                if ($user) {
                    $userEntity = new UserEntity($user->toArray());
                    $userEntity->password = null;

                    return $userEntity;
                }

                return null;
            }
        );
    }
}
