<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Events\Listeners;

use App\Models\Exceptions\RecordExistException;
use App\Modules\OAuth\Models\OAuthClientEloquent;

/**
 * Класс обработчик событий для модели модулей.
 */
class OAuthClientEloquentListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param  OAuthClientEloquent  $oAuthClientEloquent  Модель для клиентов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function creating(OAuthClientEloquent $oAuthClientEloquent): bool
    {
        $result = $oAuthClientEloquent->newQuery()
            ->where('user_id', $oAuthClientEloquent->user_id)
            ->where('secret', $oAuthClientEloquent->secret)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('oauth::events.listeners.oAuthClientEloquentListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param  OAuthClientEloquent  $oAuthClientEloquent  Модель для клиентов.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordExistException
     */
    public function updating(OAuthClientEloquent $oAuthClientEloquent): bool
    {
        $result = $oAuthClientEloquent->newQuery()
            ->where('id', '!=', $oAuthClientEloquent->id)
            ->where('user_id', $oAuthClientEloquent->user_id)
            ->where('secret', $oAuthClientEloquent->secret)
            ->first();

        if ($result) {
            throw new RecordExistException(trans('oauth::events.listeners.oAuthClientEloquentListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param  OAuthClientEloquent  $oAuthClientEloquent  Модель для клиентов.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(OAuthClientEloquent $oAuthClientEloquent): bool
    {
        $oAuthClientEloquent->deleteRelation($oAuthClientEloquent->tokens());

        return true;
    }
}
