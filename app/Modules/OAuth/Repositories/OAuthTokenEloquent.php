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
use App\Modules\OAuth\Entities\OAuthToken;
use Carbon\Carbon;

/**
 * Класс репозитория токенов на основе Eloquent.
 */
class OAuthTokenEloquent extends Repository
{
    /**
     * Получение токена.
     *
     * @param string|int|null $userId ID пользователя.
     * @param string|null $token Токен.
     * @param string|int|null $oauthTokenId ID токена.
     * @param Carbon|null $expiresAt Дата действия.
     * @return OAuthToken|null Сущность токена.
     *
     * @throws ParameterInvalidException
     */
    public function get(
        string|int|null $userId = null,
        ?string         $token = null,
        string|int|null $oauthTokenId = null,
        ?Carbon         $expiresAt = null
    ): ?OAuthToken
    {
        $query = $this->newInstance()->newQuery();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($token) {
            $query->where('token', $token);
        }

        if ($oauthTokenId) {
            $query->where('id', $oauthTokenId);
        }

        if ($expiresAt) {
            $query->where('expires_at', '<=', $expiresAt);
        }

        $item = $query->first();

        return $item ? OAuthToken::from($item->toArray()) : null;
    }

    /**
     * Создание токена.
     *
     * @param OAuthToken $entity Сущность токена.
     *
     * @return int|string ID токена.
     *
     * @throws ParameterInvalidException
     */
    public function create(OAuthToken $entity): int|string
    {
        $model = $this->newInstance();

        $model->user_id = $entity->user_id;
        $model->token = $entity->token;
        $model->expires_at = $entity->expires_at;

        $model->save();

        return $model->id;
    }

    /**
     * Обновление токена.
     *
     * @param string|int $id ID токена.
     * @param OAuthToken $entity Сущность токена.
     *
     * @return int|string ID токена.
     *
     * @throws ParameterInvalidException
     * @throws RecordNotExistException
     */
    public function update(string|int $id, OAuthToken $entity): int|string
    {
        $model = $this->newInstance()->newQuery()->find($id);

        if ($model) {
            $model->user_id = $entity->user_id;
            $model->token = $entity->token;
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
