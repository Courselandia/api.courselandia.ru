<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Entities\AccessVerified;
use App\Modules\Access\Data\Decorators\AccessVerify;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для валидации пользователя.
 */
class AccessVerifyDecorator extends Decorator
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Код верификации.
     *
     * @var string
     */
    private string $code;

    /**
     * @param int|string $id ID пользователя.
     * @param string $code Код верификации.
     */
    public function __construct(int|string $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessVerified Вернет результат действия.
     */
    public function run(): AccessVerified
    {
        $accessVerify = new AccessVerify($this->id, $this->code);

        /**
         * @var AccessVerify $data
         */
        $data = app(Pipeline::class)
            ->send($accessVerify)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessVerified($data->user, $data->token->accessToken, $data->token->refreshToken);
    }
}
