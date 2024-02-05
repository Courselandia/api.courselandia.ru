<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Decorators\Admin\User;

use App\Models\Decorator;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Entities\User;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для создания пользователя.
 */
class UserCreateDecorator extends Decorator
{
    /**
     * Данные для декоратора создания пользователя.
     *
     * @var UserCreate
     */
    private UserCreate $data;

    /**
     * @param UserCreate $data Данные для декоратора создания пользователя.
     */
    public function __construct(UserCreate $data)
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
         * @var UserCreate $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return $data->user;
    }
}
