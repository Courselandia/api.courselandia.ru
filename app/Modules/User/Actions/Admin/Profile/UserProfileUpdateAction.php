<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\Profile;

use App\Models\Action;
use App\Modules\User\Decorators\Admin\User\UserUpdateDecorator;
use App\Modules\User\Entities\User;
use App\Modules\User\Pipes\Admin\User\Create\GetPipe;
use App\Modules\User\Pipes\Admin\Profile\Update\UpdatePipe;
use App\Modules\User\Pipes\Admin\User\Update\ImagePipe;
use Illuminate\Http\UploadedFile;

/**
 * Обновления профиля пользователя.
 */
class UserProfileUpdateAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

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
     * Метод запуска логики.
     *
     * @return User Вернет результаты исполнения.
     */
    public function run(): User
    {
        $decorator = app(UserUpdateDecorator::class);
        $decorator->id = $this->id;
        $decorator->image = $this->image;
        $decorator->first_name = $this->first_name;
        $decorator->second_name = $this->second_name;
        $decorator->phone = $this->phone;

        $decorator->setActions([
            UpdatePipe::class,
            ImagePipe::class,
            GetPipe::class
        ]);

        return $decorator->run();
    }
}
