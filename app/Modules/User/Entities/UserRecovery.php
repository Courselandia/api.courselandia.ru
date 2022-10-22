<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use Hash;
use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для восстановления пароля пользователя.
 */
class UserRecovery extends Entity
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
     * Код восстановления.
     *
     * @var string|null
     */
    public ?string $code = null;

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
     * Генерация кода восстановления.
     *
     * @return string Вернет код.
     */
    public static function generateCode(): string
    {
        return Hash::make(intval(Carbon::now()->format('U')) + rand(1, 100000000));
    }
}