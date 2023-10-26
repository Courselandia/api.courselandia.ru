<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use Hash;
use Cache;
use OAuth;
use Config;
use Util;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\OAuth\Entities\Token;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Models\User;
use App\Models\Action;
use ReflectionException;

/**
 * Класс действия для генерации токена.
 */
class AccessApiTokenAction extends Action
{
    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * Логин пользователя.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Пропустить проверку пароля пользователя.
     *
     * @var bool
     */
    public bool $force = false;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     */
    public function run(): AccessApiToken
    {
        $cacheKey = Util::getKey('access', 'user', 'login', $this->login, 'role', 'verification');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $user = User::where('login', $this->login)
                    ->with([
                        'role',
                        'verification',
                    ])
                    ->active()
                    ->first();

                if ($user) {
                    return new UserEntity($user->toArray());
                }

                return null;
            }
        );

        if ($user) {
            $check = false;

            if ($this->password) {
                $check = Hash::check($this->password, $user->password);
            } elseif ($this->force) {
                $check = true;
            }

            if ($check) {
                if ($this->remember) {
                    OAuth::setSecondsTokenLife(Config::get('token.remember.token_life'))
                        ->setSecondsRefreshTokenLife(Config::get('token.remember.refresh_token_life'));
                }

                /**
                 * @var Token $token
                 */
                $token = OAuth::token($user->id);

                $accessApiToken = new AccessApiToken();
                $accessApiToken->accessToken = $token->accessToken;
                $accessApiToken->refreshToken = $token->refreshToken;

                $id = $user->id;
                $cacheKey = Util::getKey('access', 'user', $user->id, 'role');

                $accessApiToken->user = Cache::tags(['access', 'user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($id) {
                        $user = User::where('id', $id)->with('role')->active()->first();

                        if ($user) {
                            return new UserEntity($user->toArray());
                        }

                        return null;
                    }
                );

                $accessApiToken->user->password = null;

                return $accessApiToken;
            }

            throw new InvalidPasswordException(trans('access::actions.accessApiTokenAction.passwordNotMatch'));
        }

        throw new UserNotExistException(trans('access::actions.accessApiTokenAction.notExistUser'));
    }
}
