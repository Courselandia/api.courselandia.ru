<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Action;
use App\Models\Exceptions\UserVerifiedException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use Cache;
use ReflectionException;
use Util;

/**
 * Отправка e-mail сообщения пользователю для верификации.
 */
class AccessSendEmailVerificationAction extends Action
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

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
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException|ReflectionException|UserVerifiedException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder();
        $query->addCondition(new RepositoryCondition('login', $this->login))
            ->setActive(true);

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            $action = app(AccessSendEmailVerificationCodeAction::class);
            $action->id = $user->id;
            $action->run();

            return true;
        }

        throw new UserNotExistException(trans('access::actions.site.accessSendEmailVerificationAction.notExistUser'));
    }
}
