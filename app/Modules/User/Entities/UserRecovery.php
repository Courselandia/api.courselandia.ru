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
 *
 * @property int|string|null $id ID записи.
 * @property int|string|null $user_id ID пользователя.
 * @property string|null $code Код восстановления.
 * @property Carbon|null $created_at Дата создания.
 * @property Carbon|null $updated_at Дата обновления.
 * @property Carbon|null $deleted_at Дата удаления.
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
    public string|null $code = null;

    /**
     * Дата создания.
     *
     * @var Carbon|null
     */
    public Carbon|null $created_at = null;

    /**
     * Дата обновления.
     *
     * @var Carbon|null
     */
    public Carbon|null $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var Carbon|null
     */
    public Carbon|null $deleted_at = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $user_id ID пользователя.
     * @param string|null $code Код восстановления.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $user_id = null,
        ?string         $code = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->code = $code;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }

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
