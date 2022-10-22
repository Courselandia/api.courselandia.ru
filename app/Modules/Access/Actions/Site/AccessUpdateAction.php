<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Modules\Access\Decorators\Site\AccessUpdateDecorator;
use App\Modules\Access\Pipes\Site\Update\DataPipe;
use App\Modules\Access\Pipes\Site\Update\UserPipe;
use App\Modules\User\Entities\User;

/**
 * Изменение информации о пользователе.
 */
class AccessUpdateAction extends Action
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
    public ?string $first_name;

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
     * Метод запуска логики.
     *
     * @return User Вернет результаты исполнения.
     */
    public function run(): User
    {
        $decorator = app(AccessUpdateDecorator::class);
        $decorator->id = $this->id;
        $decorator->first_name = $this->first_name;
        $decorator->second_name = $this->second_name;
        $decorator->phone = $this->phone;

        return $decorator->setActions([
            UserPipe::class,
            DataPipe::class,
        ])->run();
    }
}
