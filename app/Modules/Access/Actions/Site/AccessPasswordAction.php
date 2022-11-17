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
use Hash;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidPasswordException;
use App\Modules\User\Models\User;
use ReflectionException;
use Util;

/**
 * Изменение пароля пользователя.
 */
class AccessPasswordAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Текущий пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password_current = null;

    /**
     * Новый пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        if ($this->id) {
            $cacheKey = Util::getKey('access', 'user', 'model', $this->id);

            $user = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    return User::active()
                        ->where('id', $this->id)
                        ->first();
                }
            );

            if ($user) {
                $check = Hash::check($this->password_current, $user->password);

                if ($check) {
                    $user->password = bcrypt($this->password);

                    $user->update($user->toArray());
                    Cache::tags(['access', 'user'])->flush();

                    return true;
                }

                throw new InvalidPasswordException(trans('access::actions.site.accessPasswordAction.passwordNotMatch'));
            }

            throw new UserNotExistException(trans('access::actions.site.accessPasswordAction.notExistUser'));
        }

        throw new UserNotExistException(trans('access::actions.site.accessPasswordAction.notExistUser'));
    }
}
