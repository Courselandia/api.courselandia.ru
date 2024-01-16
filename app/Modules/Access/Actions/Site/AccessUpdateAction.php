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
use App\Modules\Access\DTO\Actions\AccessUpdate;
use App\Modules\Access\DTO\Decorators\AccessUpdate as AccessUpdateDtoDecorator;
use App\Modules\Access\Pipes\Site\Update\UserPipe;
use App\Modules\User\Entities\User;

/**
 * Изменение информации о пользователе.
 */
class AccessUpdateAction extends Action
{
    /**
     * @var AccessUpdate DTO для действия изменения информации о пользователе.
     */
    private AccessUpdate $data;

    public function __construct(AccessUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return User Вернет результаты исполнения.
     */
    public function run(): User
    {
        $decorator = new AccessUpdateDecorator(AccessUpdateDtoDecorator::from($this->data->toArray()));

        return $decorator->setActions([
            UserPipe::class,
        ])->run();
    }
}
