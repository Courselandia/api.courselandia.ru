<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Entities\AccessSignedUp;
use App\Modules\Access\DTO\Decorators\AccessSignUp as AccessSignUp;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для регистрации нового пользователя.
 */
class AccessSignUpDecorator extends Decorator
{
    /**
     * DTO для декоратора регистрации.
     *
     * @var AccessSignUp
     */
    private AccessSignUp $data;

    /**
     * @param AccessSignUp $data DTO для декоратора регистрации.
     */
    public function __construct(AccessSignUp $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSignedUp Сущность для зарегистрированного пользователя.
     */
    public function run(): AccessSignedUp
    {
        /**
         * @var AccessSignUp $data
         */
        $data =  app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessSignedUp($data->user, $data->token->accessToken, $data->token->refreshToken);
    }
}
