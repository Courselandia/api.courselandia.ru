<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Entities\AccessSignedIn;
use Illuminate\Pipeline\Pipeline;
use App\Modules\Access\DTO\Decorators\AccessSignIn as AccessSignInDto;

/**
 * Класс декоратор для авторизации пользователя.
 */
class AccessSignInDecorator extends Decorator
{
    /**
     * DTO для авторизации пользователя.
     *
     * @var AccessSignInDto
     */
    private AccessSignInDto $data;

    /**
     * DTO для авторизации пользователя.
     *
     * @param AccessSignInDto $data
     */
    public function __construct(AccessSignInDto $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSignedIn Сущность для авторизованного пользователя.
     */
    public function run(): AccessSignedIn
    {
        /**
         * @var AccessSignInDto $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessSignedIn($data->user, $data->token->getAccessToken(), $data->token->getRefreshToken());
    }
}
