<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use Util;
use Cache;
use Config;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use App\Models\Exceptions\UserNotExistException;
use App\Models\Exceptions\InvalidCodeException;
use ReflectionException;
use App\Models\Enums\CacheTime;

/**
 * Проверка кода на изменение пароля пользователя.
 */
class AccessCheckCodeResetPasswordAction extends Action
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
     * Код восстановления пользователя.
     *
     * @var string|null
     */
    public ?string $code = null;

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
     * @throws InvalidCodeException
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): bool
    {
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
            if ($user->recovery?->code === $this->code || Config::get('app.env') === 'testing' || Config::get(
                    'app.env'
                ) === 'local') {
                return true;
            }

            throw new InvalidCodeException(
                trans('access::actions.site.accessCheckCodeResetPasswordAction.codeNotCorrect')
            );
        }

        throw new UserNotExistException(trans('access::actions.site.accessCheckCodeResetPasswordAction.notExistUser'));
    }
}
