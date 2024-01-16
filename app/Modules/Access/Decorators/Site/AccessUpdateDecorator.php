<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\DTO\Decorators\AccessUpdate;
use App\Modules\User\Entities\User;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для обновления данных пользователя.
 */
class AccessUpdateDecorator extends Decorator
{
    /**
     * @var AccessUpdate DTO для декоратора изменения информации о пользователе.
     */
    private AccessUpdate $data;

    /**
     * @param AccessUpdate $data DTO для декоратора изменения информации о пользователе.
     */
    public function __construct(AccessUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return User Вернет результат исполнения.
     */
    public function run(): User
    {
        /**
         * @var AccessUpdate $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        /*print_r([
            ...$data->user->toArray(),
            'password' => null,
        ]);*/

        return User::from([
            ...$data->user->toArray(),
            'password' => null,
        ]);
    }
}
