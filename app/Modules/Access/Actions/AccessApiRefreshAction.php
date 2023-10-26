<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use App\Models\Enums\CacheTime;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\OAuth\Entities\Token;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Config;
use OAuth;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Models\User;
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
    public bool $remember = false;

    /**
     * Токен обновления.
     *
     * @var string|null
     */
    public ?string $refreshToken = null;

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

        $accessApiToken = new AccessApiToken();
        $accessApiToken->accessToken = $token->accessToken;
        $accessApiToken->refreshToken = $token->refreshToken;

        $id = $data->user;
        $cacheKey = Util::getKey('access', 'user', $id, 'role');

        $accessApiToken->user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($id) {
                $user = User::where('id', $id)
                    ->with('role')
                    ->active()
                    ->first();

                if ($user) {
                    return new UserEntity($user->toArray());
                }

                return null;
            }
        );

        $accessApiToken->user->password = null;

        return $accessApiToken;
    }
}
