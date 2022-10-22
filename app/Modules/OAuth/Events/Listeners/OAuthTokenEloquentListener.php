<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\OAuth\Models\OAuthTokenEloquent;

/**
 * Класс обработчик событий для модели токенов.
 */
class OAuthTokenEloquentListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  OAuthTokenEloquent  $oAuthTokenEloquent  Модель для токенов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(OAuthTokenEloquent $oAuthTokenEloquent): bool
    {
        $result = $oAuthTokenEloquent->newQuery()
            ->where('oauth_client_id', $oAuthTokenEloquent->oauth_client_id)
            ->where('token', $oAuthTokenEloquent->token)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('oauth::events.listeners.oAuthTokenEloquentListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  OAuthTokenEloquent  $oAuthTokenEloquent  Модель для токенов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(OAuthTokenEloquent $oAuthTokenEloquent): bool
    {
        $result = $oAuthTokenEloquent->newQuery()
            ->where('id', '!=', $oAuthTokenEloquent->id)
            ->where('oauth_client_id', $oAuthTokenEloquent->oauth_client_id)
            ->where('token', $oAuthTokenEloquent->token)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('oauth::events.listeners.oAuthTokenEloquentListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param  OAuthTokenEloquent  $oAuthTokenEloquent  Модель для токенов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(OAuthTokenEloquent $oAuthTokenEloquent): bool
    {
        $oAuthTokenEloquent->deleteRelation($oAuthTokenEloquent->refreshToken());

        return true;
    }
}
