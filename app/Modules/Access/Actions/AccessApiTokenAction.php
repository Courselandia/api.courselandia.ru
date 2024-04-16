<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\Access\Data\Actions\AccessApiToken as AccessApiTokenData;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\OAuth\Values\Token;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use Config;
use Hash;
use OAuth;
use ReflectionException;
use Util;

/**
 * Класс действия для генерации токена.
 */
class AccessApiTokenAction extends Action
{
    /**
     * Данные для генерации токена.
     *
     * @var AccessApiTokenData
     */
    private AccessApiTokenData $data;

    public function __construct(AccessApiTokenData $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ReflectionException
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     */
    public function run(): AccessApiToken
    {
        $cacheKey = Util::getKey('access', 'user', 'login', $this->data->login, 'role', 'verification');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $user = User::where('login', $this->data->login)
                    ->with([
                        'role',
                        'verification',
                    ])
                    ->active()
                    ->first();

                return $user ? UserEntity::from($user->toArray()) : null;
            }
        );

        if ($user) {
            $check = false;

            if ($this->data->password) {
                $check = Hash::check($this->data->password, $user->password);
            } elseif ($this->data->force) {
                $check = true;
            }

            if ($check) {
                if ($this->data->remember) {
                    OAuth::setSecondsTokenLife(Config::get('token.remember.token_life'))
                        ->setSecondsRefreshTokenLife(Config::get('token.remember.refresh_token_life'));
                }

                /**
                 * @var Token $token
                 */
                $token = OAuth::token($user->id);
                $id = $user->id;
                $cacheKey = Util::getKey('access', 'user', $user->id, 'role');

                $user = Cache::tags(['access', 'user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($id) {
                        $user = User::where('id', $id)->with('role')->active()->first();

                        if ($user) {
                            return UserEntity::from($user->toArray());
                        }

                        return null;
                    }
                );

                $user->password = null;

                return new AccessApiToken($token->getAccessToken(), $token->getRefreshToken(), $user);
            }

            throw new InvalidPasswordException(trans('access::actions.accessApiTokenAction.passwordNotMatch'));
        }

        throw new UserNotExistException(trans('access::actions.accessApiTokenAction.notExistUser'));
    }
}
