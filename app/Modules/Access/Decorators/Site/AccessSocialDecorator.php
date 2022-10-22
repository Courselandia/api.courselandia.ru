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
use App\Modules\Access\Entities\AccessSocial;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для авторизации через социальные сети.
 */
class AccessSocialDecorator extends Decorator
{
    /**
     * ID пользователя.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $first_name = null;

    /**
     * Фамилия.
     *
     * @var string|null
     */
    public ?string $second_name = null;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verified = false;

    /**
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public ?string $uid = null;

    /**
     * Название социальной сети.
     *
     * @var string|null
     */
    public ?string $social = null;

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSignedUp|null Сущность для зарегистрированного пользователя.
     */
    public function run(): ?AccessSignedUp
    {
        $accessSocial = new AccessSocial();
        $accessSocial->id = $this->id;
        $accessSocial->login = $this->login;
        $accessSocial->first_name = $this->first_name;
        $accessSocial->second_name = $this->second_name;
        $accessSocial->verified = $this->verified;
        $accessSocial->uid = $this->uid;
        $accessSocial->social = $this->social;

        return app(Pipeline::class)
            ->send($accessSocial)
            ->through($this->getActions())
            ->thenReturn();
    }
}
