<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Data\Actions;

use App\Models\Data;
use Illuminate\Http\UploadedFile;

/**
 * Данные для действия обновления профиля пользователя.
 */
class UserProfileUpdate extends Data
{
    /**
     * ID пользователя.
     *
     * @var string|int
     */
    public string|int $id;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name = null;

    /**
     * Фамилия.
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
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * @param string|int $id ID пользователя.
     * @param string|null $first_name Имя.
     * @param string|null $second_name Фамилия.
     * @param string|null $phone Телефон.
     * @param UploadedFile|null $image Изображение.
     */
    public function __construct(
        string|int    $id,
        ?string       $first_name = null,
        ?string       $second_name = null,
        ?string       $phone = null,
        ?UploadedFile $image = null
    )
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->phone = $phone;
        $this->image = $image;
    }
}
