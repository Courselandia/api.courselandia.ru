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
use App\Modules\Access\Entities\AccessSignUp;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для регистрации нового пользователя.
 */
class AccessSignUpDecorator extends Decorator
{
    /**
     * Логин.
     *
     * @var string|null
     */
    public ?string $login = null;

    /**
     * Пароль.
     *
     * @var string|null
     */
    public ?string $password = null;

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
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Статус верификации.
     *
     * @var bool
     */
    public bool $verify = false;

    /**
     * Уникальный индикационный номер для авторизации через соц сети.
     *
     * @var string|null
     */
    public ?string $uid = null;

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessSignedUp Вернет массив данных при выполнении действия.
     */
    public function run(): AccessSignedUp
    {
        $accessSignUp = new AccessSignUp();
        $accessSignUp->login = $this->login;
        $accessSignUp->password = $this->password;
        $accessSignUp->first_name = $this->first_name;
        $accessSignUp->second_name = $this->second_name;
        $accessSignUp->phone = $this->phone;
        $accessSignUp->verify = $this->verify;
        $accessSignUp->uid = $this->uid;

        return app(Pipeline::class)
            ->send($accessSignUp)
            ->through($this->getActions())
            ->thenReturn();
    }
}
