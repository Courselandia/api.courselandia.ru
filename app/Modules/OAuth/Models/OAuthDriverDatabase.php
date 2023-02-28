<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use Util;
use Cache;
use Config;
use stdClass;
use Carbon\Carbon;
use App\Models\Enums\CacheTime;
use App\Modules\OAuth\Entities\OAuthClient;
use App\Models\Enums\OperatorQuery;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\OAuth\Entities\OAuthRefresh;
use App\Modules\OAuth\Entities\OAuthToken;
use App\Modules\User\Models\User;
use App\Modules\OAuth\Contracts\OAuthDriver;
use App\Modules\OAuth\Repositories\OAuthClientEloquent;
use App\Modules\OAuth\Repositories\OAuthTokenEloquent;
use App\Modules\OAuth\Repositories\OAuthRefreshTokenEloquent;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidFormatException;
use App\Modules\OAuth\Entities\Token;

/**
 * Класс драйвер работы с токенами в базе данных.
 */
class OAuthDriverDatabase extends OAuthDriver
{
    /**
     * Репозиторий клиентов.
     *
     * @var OAuthClientEloquent
     */
    private OAuthClientEloquent $oAuthClientEloquent;

    /**
     * Репозиторий токенов.
     *
     * @var OAuthTokenEloquent
     */
    private OAuthTokenEloquent $oAuthTokenEloquent;

    /**
     * Репозиторий токенов обновления.
     *
     * @var OAuthRefreshTokenEloquent
     */
    private OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent;

    /**
     * Количество секунд жизни токена.
     *
     * @var int
     */
    private int $secondsTokenLife;

    /**
     * Количество секунд жизни секретного ключа.
     *
     * @var int
     */
    protected int $secondsSecretLife;

    /**
     * Количество секунд жизни токена обновления.
     *
     * @var int
     */
    private int $secondsRefreshTokenLife;

    /**
     * Конструктор.
     *
     * @param  OAuthClientEloquent  $oAuthClientEloquent  Репозиторий клиентов.
     * @param  OAuthTokenEloquent  $oAuthTokenEloquent  Репозиторий токенов.
     * @param  OAuthRefreshTokenEloquent  $oAuthRefreshTokenEloquent  Репозиторий токенов обновления.
     */
    public function __construct(
        OAuthClientEloquent $oAuthClientEloquent,
        OAuthTokenEloquent $oAuthTokenEloquent,
        OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent
    ) {
        $this->oAuthClientEloquent = $oAuthClientEloquent;
        $this->oAuthTokenEloquent = $oAuthTokenEloquent;
        $this->oAuthRefreshTokenEloquent = $oAuthRefreshTokenEloquent;
        $this->secondsTokenLife = Config::get('token.token_life', 3600);
        $this->secondsSecretLife = Config::get('token.secret_life', 3600 * 24 * 30);
        $this->secondsRefreshTokenLife = Config::get('token.refresh_token_life', 3600 * 24 * 2);
    }

    /**
     * Получение количество секунд жизни токена.
     *
     * @return int Количество секунд.
     */
    public function getSecondsTokenLife(): int
    {
        return $this->secondsTokenLife;
    }

    /**
     * Установка количество секунд жизни токена.
     *
     * @param  int  $seconds  Количество секунд.
     *
     * @return OAuthDriverDatabase Объект драйвера работы с токенами в базе данных.
     */
    public function setSecondsTokenLife(int $seconds): OAuthDriverDatabase
    {
        $this->secondsTokenLife = $seconds;

        return $this;
    }

    /**
     * Получение количество секунд жизни секретного ключа.
     *
     * @return int Количество секунд.
     */
    public function getSecondsSecretLife(): int
    {
        return $this->secondsSecretLife;
    }

    /**
     * Установка количество секунд жизни секретного ключа.
     *
     * @param  int  $seconds  Количество секунд.
     *
     * @return OAuthDriverDatabase Объект драйвера работы с токенами в базе данных.
     */
    public function setSecondsSecretLife(int $seconds): OAuthDriverDatabase
    {
        $this->secondsSecretLife = $seconds;

        return $this;
    }

    /**
     * Получение количество секунд жизни токена обновления.
     *
     * @return int Количество секунд.
     */
    public function getSecondsRefreshTokenLife(): int
    {
        return $this->secondsRefreshTokenLife;
    }

    /**
     * Установка количество секунд жизни токена обновления.
     *
     * @param  int  $seconds  Количество секунд.
     *
     * @return OAuthDriverDatabase Объект драйвера работы с токенами в базе данных.
     */
    public function setSecondsRefreshTokenLife(int $seconds): OAuthDriverDatabase
    {
        $this->secondsRefreshTokenLife = $seconds;

        return $this;
    }

    /**
     * Создание секретного ключа.
     *
     * @param  int  $userId  ID пользователя.
     *
     * @return string Вернет секретный ключ клиента.
     * ParameterInvalidException|ReflectionException
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function secret(int $userId): string
    {
        $expiresAtToken = Carbon::now()->addSeconds($this->getSecondsSecretLife());
        $expiresAtRefreshToken = Carbon::now()->addSeconds($this->getSecondsRefreshTokenLife());

        $value = new stdClass();
        $value->user = $userId;

        $issue = $this->issue($value, $expiresAtToken, $expiresAtRefreshToken);

        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('user_id', $userId))
            ->addCondition(new RepositoryCondition('secret', $issue->accessToken));

        $cacheKey = Util::getKey('oAuth', 'client', $query);

        $client = Cache::tags(['oAuth', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->oAuthClientEloquent->get($query);
            }
        );

        if ($client) {
            $client->expires_at = $expiresAtToken;

            $this->oAuthClientEloquent->update($client->id, $client);
        } else {
            $client = new OAuthClient();
            $client->user_id = $userId;
            $client->secret = $issue->accessToken;
            $client->expires_at = $expiresAtToken;

            $this->oAuthClientEloquent->create($client);
        }

        Cache::tags(['oAuth', 'user'])->flush();

        return $issue->accessToken;
    }

    /**
     * Получения токена.
     *
     * @param  string  $secret  Секретный ключ клиента.
     *
     * @return Token Токен.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws InvalidFormatException
     * @throws ParameterInvalidException
     */
    public function token(string $secret): Token
    {
        $value = $this->decode($secret, 'accessToken');

        $userId = $value->user;
        $cacheKey = Util::getKey('user', 'model', $value->user);

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($userId) {
                return User::find($userId);
            }
        );

        if ($user) {
            $query = new RepositoryQueryBuilder();
            $query->addCondition(new RepositoryCondition('secret', $secret))
                ->addCondition(new RepositoryCondition('user_id', $value->user));

            $cacheKey = Util::getKey('oAuth', 'client', $query);

            $clientEntity = Cache::tags(['oAuth', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->oAuthClientEloquent->get($query);
                }
            );

            if ($clientEntity) {
                $expiresAtToken = Carbon::now()->addSeconds($this->getSecondsTokenLife());
                $expiresAtRefreshToken = Carbon::now()->addSeconds($this->getSecondsRefreshTokenLife());

                $valueAccessToken = new stdClass();
                $valueAccessToken->user = $value->user;
                $valueAccessToken->client = $clientEntity->id;

                $issuedToken = $this->issue($valueAccessToken, $expiresAtToken, $expiresAtRefreshToken);

                $query = new RepositoryQueryBuilder();
                $query->addCondition(new RepositoryCondition('oauth_client_id', $clientEntity->id))
                    ->addCondition(new RepositoryCondition('token', $issuedToken->accessToken));

                $tokenEntity = $this->oAuthTokenEloquent->get($query);

                if ($tokenEntity) {
                    $tokenEntity->expires_at = $expiresAtToken;
                    $this->oAuthTokenEloquent->update($tokenEntity->id, $tokenEntity);
                } else {
                    $tokenEntity = new OAuthToken();
                    $tokenEntity->oauth_client_id = $clientEntity->id;
                    $tokenEntity->token = $issuedToken->accessToken;
                    $tokenEntity->expires_at = $expiresAtToken;

                    $tokenEntity->id = $this->oAuthTokenEloquent->create($tokenEntity);
                }

                $query = new RepositoryQueryBuilder();
                $query->addCondition(new RepositoryCondition('oauth_token_id', $tokenEntity->id))
                    ->addCondition(new RepositoryCondition('refresh_token', $issuedToken->refreshToken));

                $cacheKey = Util::getKey('oAuth', 'refresh', $query);

                $refreshToken = Cache::tags(['oAuth', 'user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($query) {
                        return $this->oAuthRefreshTokenEloquent->get($query);
                    }
                );

                if ($refreshToken) {
                    $refreshToken->expires_at = $expiresAtRefreshToken;
                    $this->oAuthRefreshTokenEloquent->update($refreshToken->id, $refreshToken);
                } else {
                    $refreshToken = new OAuthRefresh();
                    $refreshToken->oauth_token_id = $tokenEntity->id;
                    $refreshToken->refresh_token = $issuedToken->refreshToken;
                    $refreshToken->expires_at = $expiresAtRefreshToken;

                    $this->oAuthRefreshTokenEloquent->create($refreshToken);
                }

                Cache::tags(['uAuth', 'user'])->flush();

                $token = new Token();
                $token->secret = $secret;
                $token->accessToken = $issuedToken->accessToken;
                $token->refreshToken = $issuedToken->refreshToken;

                return $token;
            }

            throw new RecordNotExistException(trans('oauth::models.oAuthDriverDatabase.noClient'));
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Абстрактный метод обновления токена.
     *
     * @param  string  $refreshToken  Токен обновления.
     *
     * @return Token Токен.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws InvalidFormatException
     * @throws ParameterInvalidException
     */
    public function refresh(string $refreshToken): Token
    {
        $value = $this->decode($refreshToken, 'refreshToken');
        $userId = $value->user;
        $cacheKey = Util::getKey('user', 'model', $value->user);

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($userId) {
                return User::find($userId);
            }
        );

        if ($user) {
            $query = new RepositoryQueryBuilder($value->client);
            $cacheKey = Util::getKey('oAuth', 'client', $query);

            $clientEntity = Cache::tags(['oAuth', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->oAuthClientEloquent->get($query);
                }
            );

            if ($clientEntity) {
                $query = new RepositoryQueryBuilder();
                $query->addCondition(new RepositoryCondition('refresh_token', $refreshToken))
                    ->addCondition(new RepositoryCondition('oauth_clients.user_id', $value->user))
                    ->addRelation('token.client');

                $cacheKey = Util::getKey('oAuth', 'refresh', $query);

                $refreshTokenEntity = Cache::tags(['oAuth', 'user'])->remember(
                    $cacheKey,
                    CacheTime::GENERAL->value,
                    function () use ($query) {
                        return $this->oAuthRefreshTokenEloquent->get($query);
                    }
                );

                if ($refreshTokenEntity) {
                    $expiresAtToken = Carbon::now()->addSeconds($this->getSecondsTokenLife());
                    $expiresAtRefreshToken = Carbon::now()->addSeconds($this->getSecondsRefreshTokenLife());

                    $valueAccessToken = new stdClass();
                    $valueAccessToken->user = $value->user;
                    $valueAccessToken->client = $clientEntity->id;

                    $issuedToken = $this->issue($valueAccessToken, $expiresAtToken, $expiresAtRefreshToken);

                    $query = new RepositoryQueryBuilder();
                    $query->addCondition(new RepositoryCondition('oauth_client_id', $clientEntity->id))
                        ->addCondition(new RepositoryCondition('token', $issuedToken->accessToken));

                    $tokenEntity = $this->oAuthTokenEloquent->get($query);

                    if ($tokenEntity) {
                        $tokenEntity->expires_at = $expiresAtToken;
                        $tokenEntity->id = $this->oAuthTokenEloquent->update($tokenEntity->id, $tokenEntity);
                    } else {
                        $tokenEntity = new OAuthToken();
                        $tokenEntity->oauth_client_id = $clientEntity->id;
                        $tokenEntity->token = $issuedToken->accessToken;
                        $tokenEntity->expires_at = $expiresAtToken;

                        $tokenEntity->id = $this->oAuthTokenEloquent->create($tokenEntity);
                    }

                    $query = new RepositoryQueryBuilder();
                    $query->addCondition(new RepositoryCondition('oauth_token_id', $tokenEntity->id))
                        ->addCondition(new RepositoryCondition('refresh_token', $issuedToken->refreshToken));

                    $cacheKey = Util::getKey('oAuth', 'refresh', $query);

                    $refreshTokenEntity = Cache::tags(['oAuth', 'user'])->remember(
                        $cacheKey,
                        CacheTime::GENERAL->value,
                        function () use ($query) {
                            return $this->oAuthRefreshTokenEloquent->get($query);
                        }
                    );

                    if ($refreshTokenEntity) {
                        $refreshTokenEntity->expires_at = $expiresAtRefreshToken;
                        $this->oAuthRefreshTokenEloquent->update($refreshTokenEntity->id, $refreshTokenEntity);
                    } else {
                        $refreshTokenEntity = new OAuthRefresh();
                        $refreshTokenEntity->oauth_token_id = $tokenEntity->id;
                        $refreshTokenEntity->refresh_token = $issuedToken->refreshToken;
                        $refreshTokenEntity->expires_at = $expiresAtRefreshToken;

                        $refreshTokenEntity->id = $this->oAuthRefreshTokenEloquent->create($refreshTokenEntity);
                    }

                    Cache::tags(['oAuth', 'user'])->flush();

                    $token = new Token();
                    $token->secret = $clientEntity->secret;
                    $token->accessToken = $issuedToken->accessToken;
                    $token->refreshToken = $issuedToken->refreshToken;

                    return $token;
                }

                throw new RecordNotExistException(trans('oauth::models.oAuthDriverDatabase.noRefreshCode'));
            }

            throw new RecordNotExistException(trans('oauth::models.oAuthDriverDatabase.noClient'));
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Проверка токена.
     *
     * @param  string  $token  Токен.
     *
     * @return bool Вернет результат проверки.
     * @throws RecordNotExistException
     * @throws InvalidFormatException
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     */
    public function check(string $token): bool
    {
        $value = $this->decode($token, 'accessToken');
        $userId = $value->user;
        $cacheKey = Util::getKey('user', 'model', $value->user);

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($userId) {
                return User::find($userId);
            }
        );

        if ($user) {
            $query = new RepositoryQueryBuilder($value->client);
            $cacheKey = Util::getKey('oAuth', 'client', $query);

            $clientEntity = Cache::tags(['aAuth', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->oAuthClientEloquent->get($query);
                }
            );

            if ($clientEntity) {
                $query = new RepositoryQueryBuilder();
                $query->addCondition(new RepositoryCondition('token', $token))
                    ->addCondition(new RepositoryCondition('oauth_clients.user_id', $value->user))
                    ->addRelation('client');

                $record = $this->oAuthTokenEloquent->get($query);

                return (bool)$record;
            }

            throw new RecordNotExistException(trans('oauth::models.oAuthDriverDatabase.noClient'));
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Очистка системы от старых токенов.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function clean(): void
    {
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('expires_at', Carbon::now(), OperatorQuery::LTE));

        $clients = $this->oAuthClientEloquent->read($query);

        foreach ($clients as $client) {
            $this->oAuthClientEloquent->destroy($client->id, true);
        }

        $tokens = $this->oAuthTokenEloquent->read($query);

        foreach ($tokens as $token) {
            $this->oAuthTokenEloquent->destroy($token->id, true);
        }
    }
}

