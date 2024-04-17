<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Repositories;

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
     * @return OAuthToken|null Сущность токена.
     */
    public function get(
        string|int|null $userId = null,
        ?string         $token = null,
        string|int|null $oauthTokenId = null
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

        $item = $query->first();

        return $item ? OAuthToken::from($item->toArray()) : null;
    }

    /**
     * Получение токенов.
     *
     * @param Carbon|null $expiresAt Дата действия.
     * @return array<int, OAuthToken>
     */
    public function find(?Carbon $expiresAt = null): array
    {
        $query = $this->newInstance()->newQuery();

        if ($expiresAt) {
            $query->where('expires_at', '<=', $expiresAt);
        }

        $items = $query->get()->toArray();

        return OAuthToken::collect($items);
    }

    /**
     * Создание токена.
     *
     * @param OAuthToken $entity Сущность токена.
     *
     * @return int|string ID токена.
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
     */
    public function destroy(int|string|array $id = null): bool
    {
        $model = $this->newInstance();

        return $model->destroy($id);
    }
}
