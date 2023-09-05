<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Contracts;

use Config;
use Carbon\Carbon;
use stdClass;
use Throwable;
use Firebase\JWT\JWT;
use App\Modules\OAuth\Entities\Token;
use App\Models\Exceptions\InvalidFormatException;
use App\Modules\OAuth\Entities\TokenPair;

/**
 * Абстрактный класс позволяющий проектировать собственные классы для хранения токенов.
 */
abstract class OAuthDriver
{
    /**
     * Абстрактный метод создания секретного ключа.
     *
     * @param  int  $userId  ID пользователя.
     *
     * @return string Вернет секретный ключ клиента.
     */
    abstract public function secret(int $userId): string;

    /**
     * Абстрактный метод получения токена.
     *
     * @param  string  $secret  Секретный ключ клиента.
     *
     * @return Token Токен.
     */
    abstract public function token(string $secret): Token;

    /**
     * Абстрактный метод обновления токена.
     *
     * @param  string  $refreshToken  Токен обновления.
     *
     * @return Token Токен.
     */
    abstract public function refresh(string $refreshToken): Token;

    /**
     * Проверка токена.
     *
     * @param  string  $token  Токен.
     *
     * @return bool Вернет результат проверки.
     */
    abstract public function check(string $token): bool;

    /**
     * Очистка системы от старых токенов.
     *
     * @return void
     */
    abstract public function clean(): void;

    /**
     * Выдача токена.
     *
     * @param  stdClass  $value  Значение для сохранения в токене.
     * @param  Carbon  $expiresAtToken  Время жизни токена.
     * @param  Carbon|null  $expiresAtRefreshToken  Время жизни токена обновления.
     *
     * @return TokenPair Вернет пару токена.
     */
    public function issue(stdClass $value, Carbon $expiresAtToken, Carbon $expiresAtRefreshToken = null): TokenPair
    {
        $time = Carbon::now()->format('U');
        $key = Config::get('app.key');
        $extendToken = $expiresAtToken->format('U') - $time;
        $extendRefreshToken = $expiresAtRefreshToken->format('U') - $time;

        $accessToken = [
            'iss' => Config::get('app.url'),
            'aud' => Config::get('app.name'),
            'exp' => $expiresAtToken->format('U'),
            'type' => 'accessToken',
            'data' => $value,
            'extend' => $extendToken,
            'time' => $time
        ];

        $accessToken = JWT::encode($accessToken, $key);

        $refreshToken = [
            'iss' => Config::get('app.url'),
            'aud' => Config::get('app.name'),
            'exp' => $expiresAtRefreshToken->format('U'),
            'type' => 'refreshToken',
            'data' => $value,
            'extend' => $extendRefreshToken,
            'time' => $time
        ];

        $refreshToken = JWT::encode($refreshToken, $key);

        $tokenPair = new TokenPair();
        $tokenPair->accessToken = $accessToken;
        $tokenPair->refreshToken = $refreshToken;

        return $tokenPair;
    }

    /**
     * Декодирование токена.
     *
     * @param  string  $token  Токен.
     * @param  string  $type  Тип токена.
     *
     * @return stdClass Вернет объект с токеном и токеном обновления.
     * @throws InvalidFormatException
     */
    public function decode(string $token, string $type): stdClass
    {
        $key = Config::get('app.key');

        try {
            $value = JWT::decode($token, $key, ['HS256']);

            if ($value) {
                if (
                    Carbon::now()->format('U') <= $value->exp
                    && $value->iss === Config::get('app.url')
                    && $value->aud === Config::get('app.name')
                    && $value->type === $type
                ) {
                    return $value->data;
                }

                throw new InvalidFormatException('The token is invalid.');
            }

            throw new InvalidFormatException('The token is invalid.');
        } catch (Throwable $error) {
            throw new InvalidFormatException('The token is invalid.');
        }
    }
}
