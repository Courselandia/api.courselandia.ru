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
use Hash;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidPasswordException;
use App\Modules\User\Repositories\User;
use ReflectionException;
use Util;

/**
 * Изменение пароля пользователя.
 */
class AccessPasswordAction extends Action
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Текущий пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password_current = null;

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
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        if ($this->id) {
            $query = new RepositoryQueryBuilder($this->id, true);
            $cacheKey = Util::getKey('access', 'user', $query);

            $user = Cache::tags(['access', 'user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->user->get($query);
                }
            );

            if ($user) {
                $check = Hash::check($this->password_current, $user->password);

                if ($check) {
                    $user->password = bcrypt($this->password);

                    $this->user->update($user->id, $user);
                    Cache::tags(['access', 'user'])->flush();

                    return true;
                }

                throw new InvalidPasswordException(trans('access::actions.site.accessPasswordAction.passwordNotMatch'));
            }

            throw new UserNotExistException(trans('access::actions.site.accessPasswordAction.notExistUser'));
        }

        throw new UserNotExistException(trans('access::actions.site.accessPasswordAction.notExistUser'));
    }
}
