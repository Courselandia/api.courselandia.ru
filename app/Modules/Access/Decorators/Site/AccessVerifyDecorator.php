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
use App\Modules\Access\Entities\AccessVerify;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для валидации пользователя.
 */
class AccessVerifyDecorator extends Decorator
{
    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Код верификации.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return AccessVerified Вернет результат действия.
     */
    public function run(): AccessVerified
    {
        $accessVerify = new AccessVerify();
        $accessVerify->id = $this->id;
        $accessVerify->code = $this->code;

        return app(Pipeline::class)
            ->send($accessVerify)
            ->through($this->getActions())
            ->thenReturn();
    }
}
