<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Models;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use Eloquent;
use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Modules\User\Repositories\User as UserRepository;

/**
 * Класс драйвер для проверки аутентификации.
 */
class AccessUserProvider implements UserProvider
{
    /**
     * Репозиторий пользователей.
     *
     * @var UserRepository
     */
    private UserRepository $user;

    /**
     * Конструктор.
     *
     * @param  UserRepository  $user  Репозиторий для таблицы пользователей.
     */
    public function __construct(UserRepository $user)
    {
        $this->user = $user;
    }

    /**
     * Возвращение пользователя по его уникальному идентификатору.
     *
     * @param  mixed  $identifier  ID пользователя.
     *
     * @return Eloquent|null
     * @throws ParameterInvalidException
     */
    public function retrieveById($identifier): ?Eloquent
    {
        $user = $this->user->get(new RepositoryQueryBuilder($identifier));

        if ($user) {
            return $this->user->newInstance($user, true);
        }

        return null;
    }

    /**
     * Возвращение пользователя через уникальный идентификатор и токен помнить меня.
     *
     * @param  mixed  $identifier  ID пользователя.
     * @param  string  $token  Токен.
     *
     * @return Eloquent|null
     * @throws ParameterInvalidException
     */
    public function retrieveByToken($identifier, $token): ?Eloquent
    {
        $query = new RepositoryQueryBuilder($identifier);
        $query->addCondition(new RepositoryCondition($this->user->getAuthIdentifierName(), $identifier));
        $query->addCondition(new RepositoryCondition($this->user->getRememberTokenName(), $token));

        $user = $this->user->get($query);

        if ($user) {
            return $this->user->newInstance($user, true);
        }

        return null;
    }

    /**
     * Обновление токена "запомнить меня" через указание пользователя.
     *
     * @param  UserContract  $user
     * @param  string  $token  Токен.
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
     * @param  array  $credentials  Параметры.
     *
     * @return Eloquent|null
     * @throws ParameterInvalidException
     */
    public function retrieveByCredentials(array $credentials): ?Eloquent
    {
        if (empty($credentials)) {
            return null;
        }

        $query = new RepositoryQueryBuilder();

        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, 'password')) {
                $query->addCondition(new RepositoryCondition($key, $value));
            }
        }

        $user = $this->user->get($query);

        if ($user) {
            return $this->user->newInstance($user, true);
        }

        return null;
    }

    /**
     * Сравнение пользователя по заданным параметрам.
     *
     * @param  UserContract  $user
     * @param  array  $credentials
     *
     * @return bool Вернет true если есть совпадение.
     */
    public function validateCredentials(UserContract $user, array $credentials): bool
    {
        return $credentials['password'] === $user->getAuthPassword();
    }
}
