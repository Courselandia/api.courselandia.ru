<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Entities;

use App\Models\Entity;
use Carbon\Carbon;
use Str;
use Illuminate\Http\UploadedFile;
use App\Modules\Image\Entities\Image;

/**
 * Сущность для пользователя.
 */
class User extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Маленькое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_big_id = null;

    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Токен.
     *
     * @var string|null
     */
    public ?string $remember_token = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name = null;

    /**
     * ID записи.
     *
     * @var string|null
     */
    public ?string $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool
     */
    public bool $two_factor = false;

    /**
     * ID записи.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Значение флагов.
     *
     * @var array|null
     */
    public ?array $flags = null;

    /**
     * Восстановление пользователя.
     *
     * @var UserRecovery|null
     */
    public ?UserRecovery $recovery = null;

    /**
     * Верификация пользователя.
     *
     * @var UserVerification|null
     */
    public ?UserVerification $verification = null;

    /**
     * Роль пользователя.
     *
     * @var UserRole|null
     */
    public ?UserRole $role = null;

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
     * Генерация пароля.
     *
     * @return mixed Вернет пароль.
     */
    public static function generatePassword(): string
    {
        return bcrypt(Str::random(8));
    }
}
