<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Config;
use OAuth;
use Hash;
use App\Modules\Access\Entities\AccessApiClient;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Action;
use App\Modules\User\Models\User;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserNotExistException;
use ReflectionException;
use Util;

/**
 * Класс действия для генерации клиента.
 */
class AccessApiClientAction extends Action
{
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
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

    /**
     * Метод запуска логики.
     *
     * @return AccessApiClient Вернет результаты исполнения.
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): AccessApiClient
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
                    OAuth::setSecondsSecretLife(Config::get('token.remember.secret_life'))
                        ->setSecondsTokenLife(Config::get('token.remember.token_life'))
                        ->setSecondsRefreshTokenLife(Config::get('token.remember.refresh_token_life'));
                }

                $secret = OAuth::secret($user->id);
                $user->password = null;

                $accessApiClient = new AccessApiClient();
                $accessApiClient->user = $user;
                $accessApiClient->secret = $secret;

                return $accessApiClient;
            }

            throw new InvalidPasswordException(trans('access::actions.accessApiClientAction.passwordNotMatch'));
        }

        throw new UserNotExistException(trans('access::actions.accessApiClientAction.notExistUser'));
    }
}
