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
use Cache;
use Config;
use OAuth;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use ReflectionException;
use Util;

/**
 * Класс действия для обновления токена.
 */
class AccessApiRefreshAction extends Action
{
    /**
     * Репозиторий пользователя.
     *
     * @var User
     */
    private User $user;

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
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

        $accessApiToken = new AccessApiToken();
        $accessApiToken->secret = $token->secret;
        $accessApiToken->accessToken = $token->accessToken;
        $accessApiToken->refreshToken = $token->refreshToken;

        $query = new RepositoryQueryBuilder($data->user, true);
        $cacheKey = Util::getKey('access', 'user', $query);

        $accessApiToken->user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        $accessApiToken->user->password = null;

        return $accessApiToken;
    }
}
