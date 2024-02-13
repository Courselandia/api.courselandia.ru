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
use App\Modules\Access\Data\Decorators\AccessSignIn as AccessSignInData;

/**
 * Класс декоратор для авторизации пользователя.
 */
class AccessSignInDecorator extends Decorator
{
    /**
     * Данные для авторизации пользователя.
     *
     * @var AccessSignInData
     */
    private AccessSignInData $data;

    /**
     * Данные для авторизации пользователя.
     *
     * @param AccessSignInData $data
     */
    public function __construct(AccessSignInData $data)
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
         * @var AccessSignInData $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessSignedIn($data->user, $data->token->getAccessToken(), $data->token->getRefreshToken());
    }
}
