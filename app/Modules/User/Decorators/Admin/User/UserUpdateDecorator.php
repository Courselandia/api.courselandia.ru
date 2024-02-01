<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Decorators\Admin\User;

use App\Models\Decorator;
use App\Modules\User\Data\Decorators\UserProfileUpdate;
use App\Modules\User\Entities\User;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для обновления пользователя.
 */
class UserUpdateDecorator extends Decorator
{
    /**
     * @var UserProfileUpdate Данные для декоратора обновления профиля пользователя.
     */
    private UserProfileUpdate $data;

    /**
     * @param UserProfileUpdate $data Данные для декоратора обновления профиля пользователя.
     */
    public function __construct(UserProfileUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return User Вернет сущность пользователя.
     */
    public function run(): User
    {
        /**
         * @var UserProfileUpdate $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return $data->user;
    }
}
