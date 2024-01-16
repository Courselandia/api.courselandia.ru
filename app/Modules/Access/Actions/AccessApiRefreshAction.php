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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\OAuth\VO\Token;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use Config;
use OAuth;
use ReflectionException;
use Util;

/**
 * Класс действия для обновления токена.
 */
class AccessApiRefreshAction extends Action
{
    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    private bool $remember = false;

    /**
     * Токен обновления.
     *
     * @var string
     */
    private string $refreshToken;

    /**
     * @param string $refreshToken Токен обновления.
     * @param bool $remember Запомнить пользователя.
     */
    public function __construct(string $refreshToken, bool $remember = false)
    {
        $this->refreshToken = $refreshToken;
        $this->remember = $remember;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): AccessApiToken
    {
        if ($this->remember) {
            OAuth::setSecondsTokenLife(Config::get('token.remember.token_life'))
                ->setSecondsRefreshTokenLife(Config::get('token.remember.refresh_token_life'));
        }

        /**
         * @var Token $token
         */
        $token = OAuth::refresh($this->refreshToken);
        $data = OAuth::decode($this->refreshToken, 'refreshToken');

        $id = $data->user;
        $cacheKey = Util::getKey('access', 'user', $id, 'role');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                $user = User::where('id', $id)
                    ->with('role')
                    ->active()
                    ->first();

                if ($user) {
                    return UserEntity::from($user->toArray());
                }

                return null;
            }
        );

        $user->password = null;

        return new AccessApiToken($token->getAccessToken(), $token->getRefreshToken(), $user);
    }
}
