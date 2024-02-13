<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\UserConfig;

use Cache;
use App\Models\Action;
use App\Modules\User\Models\User;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;

/**
 * Обновление конфигураций пользователя.
 */
class UserConfigUpdateAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Массив данных для сохранения в виде флагов.
     *
     * @var array
     */
    private array $data;

    /**
     * @param int|string $id ID пользователей.
     * @param array $data Массив данных для сохранения в виде флагов.
     */
    public function __construct(int|string $id, array $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws UserNotExistException
     */
    public function run(): array
    {
        if ($this->id) {
            $action = new UserGetAction($this->id);
            $user = $action->run();

            if ($user) {
                User::find($this->id)
                    ->setFlags($this->data)
                    ->save();

                Cache::tags(['user'])->flush();

                $action = new UserConfigGetAction($this->id);
                return $action->run();
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
    }
}
