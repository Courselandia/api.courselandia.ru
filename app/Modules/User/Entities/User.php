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
     * @var ?Image
     */
    public ?Image $image_small_id = null;

    /**
     * Среднее изображение.
     *
     * @var ?Image
     */
    public ?Image $image_middle_id = null;

    /**
     * Большое изображение.
     *
     * @var ?Image
     */
    public ?Image $image_big_id = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    //public ?UploadedFile $image = null;

    /**
     * Логин.
     *
     * @var string|null
     */
    public string|null $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public string|null $password = null;

    /**
     * Токен.
     *
     * @var string|null
     */
    public string|null $remember_token = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public string|null $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public string|null $second_name = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public string|null $phone = null;

    /**
     * Двухфакторная аутентификация.
     *
     * @var bool|null
     */
    public bool|null $two_factor = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public bool|null $status = null;

    /**
     * Значение флагов.
     *
     * @var array|null
     */
    public array|null $flags = null;

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
     * @var Carbon|null
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var Carbon|null
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var Carbon|null
     */
    public ?Carbon $deleted_at = null;

    /**
     * @param int|string|null $id ID записи.
     * @param Image|null $image_small_id Маленькое изображение.
     * @param Image|null $image_middle_id Среднее изображение.
     * @param Image|null $image_big_id Большое изображение.
     * @param UploadedFile|null $image Изображение.
     * @param string|null $login Логин.
     * @param string|null $password Пароль.
     * @param string|null $remember_token Токен.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param bool|null $two_factor Двухфакторная аутентификация.
     * @param bool|null $status Статус.
     * @param array|null $flags Значение флагов.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param UserRecovery|null $recovery Восстановление пользователя.
     * @param UserVerification|null $verification Верификация пользователя.
     * @param UserRole|null $role Роль пользователя.
     */
    public function __construct(
        int|string|null   $id = null,
        ?Image            $image_small_id = null,
        ?Image            $image_middle_id = null,
        ?Image            $image_big_id = null,
        //?UploadedFile     $image = null,
        ?string           $login = null,
        ?string           $password = null,
        ?string           $remember_token = null,
        ?string           $first_name = null,
        ?string           $second_name = null,
        ?string           $phone = null,
        ?bool             $two_factor = false,
        ?bool             $status = null,
        ?array            $flags = null,
        ?Carbon           $created_at = null,
        ?Carbon           $updated_at = null,
        ?Carbon           $deleted_at = null,
        ?UserRecovery     $recovery = null,
        ?UserVerification $verification = null,
        ?UserRole         $role = null
    )
    {
        $this->id = $id;
        $this->image_small_id = $image_small_id;
        $this->image_middle_id = $image_middle_id;
        $this->image_big_id = $image_big_id;
        $this->login = $login;
        $this->password = $password;
        $this->remember_token = $remember_token;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->two_factor = $two_factor;
        $this->status = $status;
        $this->flags = $flags;
        $this->recovery = $recovery;
        $this->verification = $verification;
        $this->role = $role;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        //$this->image = $image;
    }

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
