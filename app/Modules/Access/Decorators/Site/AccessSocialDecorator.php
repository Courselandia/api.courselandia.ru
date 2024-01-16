<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\DTO\Decorators\AccessSocial as AccessSocialDto;
use App\Modules\Access\Entities\AccessSocial;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для авторизации через социальные сети.
 */
class AccessSocialDecorator extends Decorator
{
    /**
     * DTO для регистрации или входа через социальную сеть.
     *
     * @var AccessSocialDto
     */
    private AccessSocialDto $data;

    /**
     * @param AccessSocialDto $data DTO для регистрации или входа через социальную сеть.
     */
    public function __construct(AccessSocialDto $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSocial Сущность для авторизованного или зарегистрированного пользователя через соц. сети.
     */
    public function run(): AccessSocial
    {
        /**
         * @var AccessSocialDto $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessSocial($data->user, $data->token->accessToken, $data->token->refreshToken);
    }
}
