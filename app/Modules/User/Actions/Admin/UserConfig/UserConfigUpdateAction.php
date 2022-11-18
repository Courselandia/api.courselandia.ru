<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\UserConfig;

use Cache;
use ReflectionException;
use App\Models\Action;
use App\Modules\User\Models\User;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Models\Exceptions\RecordNotExistException;

/**
 * Обновление конфигураций пользователя.
 */
class UserConfigUpdateAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Массив данных для сохранения в виде флагов.
     *
     * @var array|null
     */
    public ?array $data = null;

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): array
    {
        if ($this->id) {
            $action = app(UserGetAction::class);
            $action->id = $this->id;
            $user = $action->run();

            if ($user) {
                User::find($this->id)
                    ->setFlags($this->data)
                    ->save();

                Cache::tags(['user'])->flush();

                $action = app(UserConfigGetAction::class);
                $action->id = $this->id;
                return $action->run();
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
    }
}
