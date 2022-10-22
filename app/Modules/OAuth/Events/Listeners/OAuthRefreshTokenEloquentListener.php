<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\OAuth\Models\OAuthRefreshTokenEloquent;

/**
 * Класс обработчик событий для модели токенов.
 */
class OAuthRefreshTokenEloquentListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  OAuthRefreshTokenEloquent  $oAuthRefreshTokenEloquent  Модель для токенов обновления.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent): bool
    {
        $result = $oAuthRefreshTokenEloquent->newQuery()
            ->where('oauth_token_id', $oAuthRefreshTokenEloquent->oauth_token_id)
            ->where('refresh_token', $oAuthRefreshTokenEloquent->refresh_token)
            ->first();

        if ($result) {
            throw new RecordExistException(
                trans('oauth::events.listeners.oAuthRefreshTokenEloquentListener.existError')
            );
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  OAuthRefreshTokenEloquent  $oAuthRefreshTokenEloquent  Модель для токенов обновления.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(OAuthRefreshTokenEloquent $oAuthRefreshTokenEloquent): bool
    {
        $result = $oAuthRefreshTokenEloquent->newQuery()
            ->where('id', '!=', $oAuthRefreshTokenEloquent->id)
            ->where('oauth_token_id', $oAuthRefreshTokenEloquent->oauth_token_id)
            ->where('refresh_token', $oAuthRefreshTokenEloquent->refresh_token)
            ->first();

        if ($result) {
            throw new RecordExistException(
                trans('oauth::events.listeners.oAuthRefreshTokenEloquentListener.existError')
            );
        }

        return true;
    }
}
