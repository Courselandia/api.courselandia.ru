<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use Cache;
use Mail;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserRecovery;
use App\Modules\Access\Emails\Site\Reset;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidCodeException;
use ReflectionException;
use Util;

/**
 * Изменение пароля пользователя.
 */
class AccessResetAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Код восстановления пользователя.
     *
     * @var string
     */
    private string $code;

    /**
     * Новый пароль пользователя.
     *
     * @var string
     */
    private string $password;

    /**
     * @param int|string $id ID пользователя.
     * @param string $code Код восстановления пользователя.
     * @param string $password Новый пароль пользователя.
     */
    public function __construct(int|string $id, string $code, string $password)
    {
        $this->id = $id;
        $this->code = $code;
        $this->password = $password;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws InvalidCodeException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $action = new AccessCheckCodeResetPasswordAction($this->id, $this->code);
        $action->run();

        $cacheKey = Util::getKey('access', 'user', 'model', $this->id);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return User::where('id', $this->id)
                    ->active()
                    ->with('recovery')
                    ->first();
            }
        );

        if ($user) {
            $user->password = bcrypt($this->password);
            $user->save();

            if ($user->recovery) {
                UserRecovery::destroy($user->recovery->id);
            }

            Cache::tags(['access', 'user'])->flush();

            Mail::to($user->login)->queue(new Reset(UserEntity::from($user->toArray())));

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessResetAction.notExistUser'));
    }
}
