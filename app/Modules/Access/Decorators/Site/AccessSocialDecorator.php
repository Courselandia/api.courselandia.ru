<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Access\Data\Decorators\AccessSocial as AccessSocialData;
use App\Modules\Access\Entities\AccessSocial;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для авторизации через социальные сети.
 */
class AccessSocialDecorator extends Decorator
{
    /**
     * Данные для регистрации или входа через социальную сеть.
     *
     * @var AccessSocialData
     */
    private AccessSocialData $data;

    /**
     * @param AccessSocialData $data Данные для регистрации или входа через социальную сеть.
     */
    public function __construct(AccessSocialData $data)
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
         * @var AccessSocialData $data
         */
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        return new AccessSocial($data->user, $data->token->accessToken, $data->token->refreshToken);
    }
}
