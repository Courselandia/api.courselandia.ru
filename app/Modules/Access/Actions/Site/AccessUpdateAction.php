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
use App\Modules\Access\Data\Actions\AccessUpdate;
use App\Modules\Access\Data\Decorators\AccessUpdate as AccessUpdateDataDecorator;
use App\Modules\Access\Pipes\Site\Update\UserPipe;
use App\Modules\User\Entities\User;

/**
 * Изменение информации о пользователе.
 */
class AccessUpdateAction extends Action
{
    /**
     * @var AccessUpdate Данные для действия изменения информации о пользователе.
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
        $decorator = new AccessUpdateDecorator(AccessUpdateDataDecorator::from($this->data->toArray()));

        return $decorator->setActions([
            UserPipe::class,
        ])->run();
    }
}
