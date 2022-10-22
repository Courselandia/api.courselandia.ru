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
use Exception;
use Mail;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Action;
use App\Modules\User\Repositories\User;
use App\Modules\User\Repositories\UserRecovery;
use App\Modules\Access\Emails\Site\Recovery;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Entities\UserRecovery as UserRecoveryEntity;
use Util;

/**
 * Отправка e-mail для восстановления пароля.
 */
class AccessForgetAction extends Action
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
     * Логин пользователя.
     *
     * @var string|null
     */
    public ?string $login = null;

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
     * @throws Exception
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder();
        $query->setActive(true)
            ->addCondition(new RepositoryCondition('login', $this->login));

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            $code = UserRecoveryEntity::generateCode();

            $query = new RepositoryQueryBuilder();
            $query->addCondition(new RepositoryCondition('user_id', $user->id));
            $cacheKey = Util::getKey('access', 'userRecovery', $query);

            $recovery = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->userRecovery->get($query);
                }
            );

            if ($recovery) {
                $recovery->code = $code;
                $this->userRecovery->update($recovery->id, $recovery);
            } else {
                $recovery = new UserRecoveryEntity();
                $recovery->user_id = $user->id;
                $recovery->code = $code;

                $this->userRecovery->create($recovery);
            }

            Cache::tags(['access', 'user'])->flush();

            Mail::to($user->login)->queue(new Recovery($user, $code));

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessForgetAction.notExistUser'));
    }
}
