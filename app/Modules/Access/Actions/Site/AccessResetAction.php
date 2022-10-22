<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use Cache;
use Mail;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use App\Modules\User\Repositories\UserRecovery;
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
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

    /**
     * Репозиторий восстановления пароля пользователя.
     *
     * @var UserRecovery
     */
    private UserRecovery $userRecovery;

    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Код восстановления пользователя.
     *
     * @var string|null
     */
    public ?string $code = null;

    /**
     * Новый пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     * @param  UserRecovery  $userRecovery  Репозиторий восстановления пароля пользователя.
     */
    public function __construct(User $user, UserRecovery $userRecovery)
    {
        $this->user = $user;
        $this->userRecovery = $userRecovery;
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
        $action = app(AccessCheckCodeResetPasswordAction::class);
        $action->id = $this->id;
        $action->code = $this->code;

        $action->run();

        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setActive(true)
            ->addRelation('recovery');

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            $user->password = bcrypt($this->password);
            $this->user->update($user->id, $user);

            if ($user->recovery) {
                $this->userRecovery->destroy($user->recovery->id);
            }

            Cache::tags(['access', 'user'])->flush();

            Mail::to($user->login)->queue(new Reset($user));

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessResetAction.notExistUser'));
    }
}
