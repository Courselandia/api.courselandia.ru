<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Action;
use App\Modules\Access\Decorators\Site\AccessVerifyDecorator;
use App\Modules\Access\Entities\AccessVerified;
use App\Modules\Access\Pipes\Site\Verified\CheckPipe;
use App\Modules\Access\Pipes\Gate\GetPipe;

/**
 * Верификация пользователя.
 */
class AccessVerifyAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * Код верификации.
     *
     * @var string
     */
    public string $code;

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
     * Метод запуска логики.
     *
     * @return AccessVerified Вернет результаты исполнения.
     */
    public function run(): AccessVerified
    {
        $decorator = new AccessVerifyDecorator($this->id, $this->code);

        return $decorator->setActions([
            CheckPipe::class,
            GetPipe::class,
        ])->run();
    }
}
