<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Repositories;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Repository;
use App\Modules\OAuth\Entities\OAuthRefresh;

/**
 * Класс репозитория токенов обновления обновления на основе Eloquent.
 */
class OAuthRefreshTokenEloquent extends Repository
{
    /**
     * Получение токена.
     *
     * @param string|int|null $oauthTokenId ID токена обновления.
     * @param string|int|null $refreshToken Дата действия.
     * @return OAuthRefresh|null Сущность токена.
     */
    public function get(string|int|null $oauthTokenId = null, string|int|null $refreshToken = null): ?OAuthRefresh
    {
        $query = $this->newInstance()->newQuery();

        if ($oauthTokenId) {
            $query->where('oauth_token_id', $oauthTokenId);
        }

        if ($refreshToken) {
            $query->where('refresh_token', $refreshToken);
        }

        $item = $query->first();

        return $item ? OAuthRefresh::from($item->toArray()) : null;
    }

    /**
     * Создание токена.
     *
     * @param OAuthRefresh $entity Сущность токена.
     *
     * @return int|string ID токена.
     */
    public function create(OAuthRefresh $entity): int|string
    {
        $model = $this->newInstance();

        $model->oauth_token_id = $entity->oauth_token_id;
        $model->refresh_token = $entity->refresh_token;
        $model->expires_at = $entity->expires_at;

        $model->save();

        return $model->id;
    }

    /**
     * Обновление токена.
     *
     * @param string|int $id ID токена обновления.
     * @param OAuthRefresh $entity Сущность токена.
     *
     * @return int|string ID токена.
     *
     * @throws ParameterInvalidException
     * @throws RecordNotExistException
     */
    public function update(string|int $id, OAuthRefresh $entity): int|string
    {
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->oauth_token_id = $entity->oauth_token_id;
            $model->refresh_token = $entity->refresh_token;
            $model->expires_at = $entity->expires_at;

            $model->save();

            return $id;
        }

        throw new RecordNotExistException('The token #' . $id . ' does not exist.');
    }

    /**
     * Удаление.
     *
     * @param int|string|array|null $id Id записи для удаления.
     *
     * @return bool Вернет булево значение успешности операции.
     * @throws ParameterInvalidException
     */
    public function destroy(int|string|array $id = null): bool
    {
        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
