<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use Cache;
use Hash;
use App\Models\Action;
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
     * @var int|string
     */
    private int|string $id;

    /**
     * Текущий пароль пользователя.
     *
     * @var string
     */
    private string $password_current;

    /**
     * Новый пароль пользователя.
     *
     * @var string
     */
    private string $password;

    /**
     * @param int|string $id ID пользователя.
     * @param string $password_current Текущий пароль пользователя.
     * @param string $password Новый пароль пользователя.
     */
    public function __construct(int|string $id, string $password_current, string $password)
    {
        $this->id = $id;
        $this->password = $password;
        $this->password_current = $password_current;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ReflectionException
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
