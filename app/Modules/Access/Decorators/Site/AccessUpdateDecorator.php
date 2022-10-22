<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Entities\AccessUpdate;
use App\Modules\User\Entities\User;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для обновления данных пользователя.
 */
class AccessUpdateDecorator extends Decorator
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
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return User Вернет результат исполнения.
     */
    public function run(): User
    {
        $accessUpdate = new AccessUpdate();
        $accessUpdate->id = $this->id;
        $accessUpdate->first_name = $this->first_name;
        $accessUpdate->second_name = $this->second_name;
        $accessUpdate->phone = $this->phone;

        return app(Pipeline::class)
            ->send($accessUpdate)
            ->through($this->getActions())
            ->thenReturn();
    }
}
