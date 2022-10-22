<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\Entity;
use Hash;
use Carbon\Carbon;

/**
 * Сущность для верификации пользователя.
 */
class UserVerification extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $user_id = null;

    /**
     * Код верификации.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Статус.
     *
     * @var bool = null
     */
    public ?bool $status = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * Генерация кода верификации.
     *
     * @param  int  $userId  ID пользователя.
     *
     * @return string Вернет код.
     */
    public static function generateCode(int $userId): string
    {
        return $userId.Hash::make(intval(Carbon::now()->format('U')) + rand(1, 100000000));
    }
}