<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Models;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\InvalidFormatException;
use App\Models\Exceptions\RecordExistException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\OAuth\Contracts\OAuthDriver;
use App\Modules\OAuth\Entities\OAuthRefresh;
use App\Modules\OAuth\Entities\OAuthToken;
use App\Modules\OAuth\Models\OAuthRefreshTokenEloquent as OAuthRefreshTokenEloquentModel;
use App\Modules\OAuth\Repositories\OAuthRefreshTokenEloquent;
use App\Modules\OAuth\Repositories\OAuthTokenEloquent;
use App\Modules\OAuth\Values\Token;
use App\Modules\User\Models\User;
use Cache;
use Carbon\Carbon;
use Config;
use stdClass;
use Util;

/**
 * Класс драйвер работы с токенами в базе данных.
 */
class OAuthDriverDatabase extends OAuthDriver
{
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
     * Количество секунд жизни токена обновления.
     *
     * @var int
     */
    private int $secondsRefreshTokenLife;

    /**
     * Конструктор.
     *
     * @param OAuthTokenEloquent $oAuthTokenEloquent Репозиторий токенов.
     * @param OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent Репозиторий токенов обновления.
     */
    public function __construct(OAuthTokenEloquent $oAuthTokenEloquent, OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent)
    {
        $this->oAuthTokenEloquent = $oAuthTokenEloquent;
        $this->oAuthRefreshTokenEloquent = $oAuthRefreshTokenEloquent;
        $this->secondsTokenLife = Config::get('token.token_life', 3600);
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
     * @param int $seconds Количество секунд.
     *
     * @return OAuthDriverDatabase Объект драйвера работы с токенами в базе данных.
     */
    public function setSecondsTokenLife(int $seconds): OAuthDriverDatabase
    {
        $this->secondsTokenLife = $seconds;

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
     * @param int $seconds Количество секунд.
     *
     * @return OAuthDriverDatabase Объект драйвера работы с токенами в базе данных.
     */
    public function setSecondsRefreshTokenLife(int $seconds): OAuthDriverDatabase
    {
        $this->secondsRefreshTokenLife = $seconds;

        return $this;
    }

    /**
     * Получения токена.
     *
     * @param int $userId ID пользователя.
     *
     * @return Token Токен.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     */
    public function token(int $userId): Token
    {
        $cacheKey = Util::getKey('user', 'model', $userId);

        $user = Cache::tags(['user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($userId) {
                return User::find($userId);
            }
        );

        if ($user) {
            $tokenEntity = $this->oAuthTokenEloquent->get($userId);
            $now = Carbon::now();

            if (
                $tokenEntity
                && $tokenEntity->expires_at > $now
                && $now->diffInSeconds($tokenEntity->expires_at) > Config::get('token.token_life', 3600)
            ) {
                $refreshTokenEntity = $this->oAuthRefreshTokenEloquent->get($tokenEntity->id);

                if ($refreshTokenEntity) {
                    return new Token($tokenEntity->token, $refreshTokenEntity->refresh_token);
                }
            }

            $expiresAtToken = Carbon::now()->addSeconds($this->getSecondsTokenLife());
            $expiresAtRefreshToken = Carbon::now()->addSeconds($this->getSecondsRefreshTokenLife());

            $valueAccessToken = new stdClass();
            $valueAccessToken->user = $userId;

            $issuedToken = $this->issue($valueAccessToken, $expiresAtToken, $expiresAtRefreshToken);
            $tokenEntity = $this->oAuthTokenEloquent->get($userId, $issuedToken->getAccessToken());

            try {
                if ($tokenEntity) {
                    $tokenEntity->expires_at = $expiresAtToken;
                    $this->oAuthTokenEloquent->update($tokenEntity->id, $tokenEntity);
                } else {
                    $tokenEntity = new OAuthToken(null, $userId, $issuedToken->getAccessToken(), $expiresAtToken);
                    $tokenEntity->id = $this->oAuthTokenEloquent->create($tokenEntity);
                }
            } catch (RecordExistException $error) {

            }

            $refreshToken = $this->oAuthRefreshTokenEloquent->get($tokenEntity->id, $issuedToken->getRefreshToken());

            try {
                if ($refreshToken) {
                    $refreshToken->expires_at = $expiresAtRefreshToken;
                    $this->oAuthRefreshTokenEloquent->update($refreshToken->id, $refreshToken);
                } else {
                    $refreshToken = new OAuthRefresh(null, $tokenEntity->id, $issuedToken->getRefreshToken(), $expiresAtRefreshToken);

                    $this->oAuthRefreshTokenEloquent->create($refreshToken);
                }
            } catch (RecordExistException $error) {

            }

            Cache::tags(['uAuth', 'user'])->flush();

            return new Token($issuedToken->getAccessToken(), $issuedToken->getRefreshToken());
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Абстрактный метод обновления токена.
     *
     * @param string $refreshToken Токен обновления.
     *
     * @return Token Токен.
     * @throws RecordNotExistException
     * @throws UserNotExistException
     * @throws InvalidFormatException
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
            $refreshTokenEntity = $this->oAuthRefreshTokenEloquent->get(null, $refreshToken);

            if ($refreshTokenEntity) {
                $tokenEntity = $this->oAuthTokenEloquent->get(null, null, $refreshTokenEntity->oauth_token_id);
                $now = Carbon::now();

                if (
                    $tokenEntity
                    && $tokenEntity->expires_at > $now
                    && $now->diffInSeconds($tokenEntity->expires_at) > Config::get('token.token_life', 3600)
                ) {
                    return new Token($tokenEntity->token, $refreshToken);
                }

                $expiresAtToken = Carbon::now()->addSeconds($this->getSecondsTokenLife());
                $expiresAtRefreshToken = Carbon::now()->addSeconds($this->getSecondsRefreshTokenLife());

                $valueAccessToken = new stdClass();
                $valueAccessToken->user = $value->user;

                $issuedToken = $this->issue($valueAccessToken, $expiresAtToken, $expiresAtRefreshToken);
                $tokenEntity = $this->oAuthTokenEloquent->get($userId, $issuedToken->getAccessToken());

                try {
                    if ($tokenEntity) {
                        $tokenEntity->expires_at = $expiresAtToken;
                        $tokenEntity->id = $this->oAuthTokenEloquent->update($tokenEntity->id, $tokenEntity);
                    } else {
                        $tokenEntity = new OAuthToken(null, $userId, $issuedToken->getAccessToken(), $expiresAtToken);
                        $tokenEntity->id = $this->oAuthTokenEloquent->create($tokenEntity);
                    }
                } catch (RecordExistException) {

                }

                $refreshTokenEntity = $this->oAuthRefreshTokenEloquent->get($tokenEntity->id, $issuedToken->getRefreshToken());

                try {
                    if ($refreshTokenEntity) {
                        $refreshTokenEntity->expires_at = $expiresAtRefreshToken;
                        $this->oAuthRefreshTokenEloquent->update($refreshTokenEntity->id, $refreshTokenEntity);
                    } else {
                        $refreshTokenEntity = new OAuthRefresh(null, $tokenEntity->id, $issuedToken->getRefreshToken(), $expiresAtRefreshToken);
                        $refreshTokenEntity->id = $this->oAuthRefreshTokenEloquent->create($refreshTokenEntity);
                    }
                } catch (RecordExistException) {

                }

                Cache::tags(['oAuth', 'user'])->flush();

                return new Token($issuedToken->getAccessToken(), $issuedToken->getRefreshToken());
            }

            throw new RecordNotExistException(trans('oauth::models.oAuthDriverDatabase.noRefreshCode'));
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Проверка токена.
     *
     * @param string $token Токен.
     *
     * @return bool Вернет результат проверки.
     * @throws InvalidFormatException
     * @throws UserNotExistException
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
            $record = $this->oAuthTokenEloquent->get($value->user, $token);

            return (bool)$record;
        }

        throw new UserNotExistException(trans('oauth::models.oAuthDriverDatabase.noUser'));
    }

    /**
     * Очистка системы от старых токенов.
     *
     * @return void
     */
    public function clean(): void
    {
        $tokens = $this->oAuthTokenEloquent->find(Carbon::now());

        foreach ($tokens as $token) {
            $this->oAuthTokenEloquent->destroy($token->id);
        }

        OAuthRefreshTokenEloquentModel::doesntHave('token')->delete();
    }
}

