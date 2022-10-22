<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions;

use Cache;
use ReflectionException;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\User\Entities\User as UserEntity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;

/**
 * Получение всех доступов к разделам.
 */
class AccessGateAction extends Action
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
        $id = $this->id;
        $query = new RepositoryQueryBuilder($id, true);
        $query->setId($id)
            ->setActive(true)
            ->setRelations([
                'verification',
                'role',
            ]);

        $key = Util::getKey('access', 'gate', $id);

        return Cache::tags(['access', 'user'])->remember(
            $key,
            CacheTime::MONTH->value,
            function () use ($query) {
                $userEntity = $this->user->get($query);

                if ($userEntity) {
                    $userEntity->password = null;

                    return $userEntity;
                }

                return null;
            }
        );
    }
}
