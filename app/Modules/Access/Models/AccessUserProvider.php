<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Models;

use Eloquent;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Modules\User\Models\User;

/**
 * Класс драйвер для проверки аутентификации.
 */
class AccessUserProvider implements UserProvider
{
    /**
     * Возвращение пользователя по его уникальному идентификатору.
     *
     * @param mixed $identifier ID пользователя.
     *
     * @return Eloquent|UserContract|null
     */
    public function retrieveById($identifier): Eloquent|UserContract|null
    {
        return User::find($identifier);
    }

    /**
     * Возвращение пользователя через уникальный идентификатор и токен помнить меня.
     *
     * @param mixed $identifier ID пользователя.
     * @param string $token Токен.
     *
     * @return Eloquent|UserContract|null
     */
    public function retrieveByToken($identifier, $token): Eloquent|UserContract|null
    {
        return User::where(User::getAuthIdentifierName(), $identifier)
            ->where(User::getAuthIdentifierName(), $token)
            ->first();
    }

    /**
     * Обновление токена "запомнить меня" через указание пользователя.
     *
     * @param UserContract $user
     * @param string $token Токен.
     *
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token): void
    {
        $user->setRememberToken($token);
        $user->save();
    }

    /**
     * Возвращение пользователя по заданным параметрам.
     *
     * @param array $credentials Параметры.
     *
     * @return Eloquent|UserContract|null
     */
    public function retrieveByCredentials(array $credentials): Eloquent|UserContract|null
    {
        if (empty($credentials)) {
            return null;
        }

        $user = new User();

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, 'password')) {
                $user->where($key, $value);
            }
        }

        return $user->first();
    }

    /**
     * Сравнение пользователя по заданным параметрам.
     *
     * @param UserContract $user
     * @param array $credentials
     *
     * @return bool Вернет true если есть совпадение.
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        return $credentials['password'] === $user->getAuthPassword();
    }
}
