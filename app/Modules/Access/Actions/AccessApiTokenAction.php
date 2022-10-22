<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use App\Models\Enums\CacheTime;
use Cache;
use OAuth;
use Config;
use App\Modules\Access\Entities\AccessApiClient;
use App\Modules\Access\Entities\AccessApiToken;
use App\Modules\OAuth\Entities\Token;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use App\Models\Action;
use ReflectionException;
use Util;

/**
 * Класс действия для генерации токена.
 */
class AccessApiTokenAction extends Action
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
     * Секретный ключ.
     *
     * @var string|null
     */
    public ?string $secret = null;

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
        $token = OAuth::token($this->secret);
        $data = OAuth::decode($this->secret, 'accessToken');

        $accessApiToken = new AccessApiToken();
        $accessApiToken->secret = $this->secret;
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
