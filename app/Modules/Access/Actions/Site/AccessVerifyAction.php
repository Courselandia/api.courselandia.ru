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
use App\Modules\Access\Pipes\Site\Verified\DataPipe;

/**
 * Верификация пользователя.
 */
class AccessVerifyAction extends Action
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
     * Метод запуска логики.
     *
     * @return AccessVerified Вернет результаты исполнения.
     */
    public function run(): AccessVerified
    {
        $decorator = app(AccessVerifyDecorator::class);
        $decorator->id = $this->id;
        $decorator->code = $this->code;

        return $decorator->setActions([
            CheckPipe::class,
            GetPipe::class,
            DataPipe::Class
        ])->run();
    }
}
