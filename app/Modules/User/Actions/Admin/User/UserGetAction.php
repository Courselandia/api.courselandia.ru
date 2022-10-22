<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\User;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Repositories\User;
use Cache;
use ReflectionException;
use Util;

/**
 * Получение пользователя.
 */
class UserGetAction extends Action
{
    /**
     * Репозиторий для выбранных групп пользователя.
     *
     * @var User
     */
    private User $user;

    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * @return UserEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?UserEntity
    {
        if ($this->id) {
            $query = new RepositoryQueryBuilder();
            $query->setId($this->id)
                ->setRelations([
                    'verification',
                    'role',
                ]);

            $cacheKey = Util::getKey('user', $query);

            return Cache::tags(['user'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->user->get($query);
                }
            );
        }

        return null;
    }
}
