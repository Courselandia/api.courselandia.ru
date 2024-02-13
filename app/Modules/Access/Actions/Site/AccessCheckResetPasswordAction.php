<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidCodeException;

/**
 * Проверка верности кода восстановления пароля.
 */
class AccessCheckResetPasswordAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Код восстановления пользователя.
     *
     * @var string
     */
    private string $code;

    /**
     * @param int|string $id ID пользователя.
     * @param string $code Код восстановления пользователя.
     */
    public function __construct(int|string $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException|InvalidCodeException
     */
    public function run(): bool
    {
        $action = new AccessCheckCodeResetPasswordAction($this->id, $this->code);

        return $action->run();
    }
}
