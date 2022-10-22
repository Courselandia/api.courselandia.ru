<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use App\Models\Enums\CacheTime;
use Cache;
use Config;
use OAuth;
use Hash;
use App\Modules\Access\Entities\AccessApiClient;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryCondition;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Models\Action;
use App\Modules\User\Repositories\User;
use App\Models\Exceptions\InvalidPasswordException;
use App\Models\Exceptions\UserNotExistException;
use ReflectionException;
use Util;

/**
 * Класс действия для генерации клиента.
 */
class AccessApiClientAction extends Action
{
    /**
     * Репозиторий для выбранных групп пользователя.
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
     * Пароль пользователя.
     *
     * @var string|null
     */
    public ?string $password = null;

    /**
     * Пропустить проверку пароля пользователя.
     *
     * @var bool
     */
    public bool $force = false;

    /**
     * Запомнить пользователя.
     *
     * @var bool
     */
    public bool $remember = false;

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
     * @return AccessApiClient Вернет результаты исполнения.
     * @throws InvalidPasswordException
     * @throws UserNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): AccessApiClient
    {
        $query = new RepositoryQueryBuilder();
        $query->setActive(true)
            ->addCondition(new RepositoryCondition('login', $this->login))
            ->addRelation('role')
            ->addRelation('verification');

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            $check = false;

            if ($this->password) {
                $check = Hash::check($this->password, $user->password);
            } elseif ($this->force) {
                $check = true;
            }

            if ($check) {
                if ($this->remember) {
                    OAuth::setSecondsSecretLife(Config::get('token.remember.secret_life'))
                        ->setSecondsTokenLife(Config::get('token.remember.token_life'))
                        ->setSecondsRefreshTokenLife(Config::get('token.remember.refresh_token_life'));
                }

                $secret = OAuth::secret($user->id);
                $user->password = null;

                $accessApiClient = new AccessApiClient();
                $accessApiClient->user = $user;
                $accessApiClient->secret = $secret;

                return $accessApiClient;
            }

            throw new InvalidPasswordException(trans('access::actions.accessApiClientAction.passwordNotMatch'));
        }

        throw new UserNotExistException(trans('access::actions.accessApiClientAction.notExistUser'));
    }
}
