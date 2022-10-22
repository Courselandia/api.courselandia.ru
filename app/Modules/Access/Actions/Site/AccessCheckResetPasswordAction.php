<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidCodeException;
use ReflectionException;

/**
 * Проверка верности кода восстановления пароля.
 */
class AccessCheckResetPasswordAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Код восстановления пользователя.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws UserNotExistException|InvalidCodeException|ParameterInvalidException|ReflectionException
     */
    public function run(): mixed
    {
        $action = app(AccessCheckCodeResetPasswordAction::class);
        $action->id = $this->id;
        $action->code = $this->code;

        return $action->run();
    }
}
