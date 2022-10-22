<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Update;

use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\UserUpdate;
use App\Modules\User\Repositories\User;
use Cache;
use Closure;
use Exception;
use ReflectionException;

/**
 * Обновление пользователя: добавление изображения пользователя.
 */
class ImagePipe implements Pipe
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

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
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|UserUpdate  $entity  Сущность для создания пользователя.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function handle(Entity|UserUpdate $entity, Closure $next): mixed
    {
        if ($entity->image) {
            $action = app(UserGetAction::class);
            $action->id = $entity->id;
            $user = $action->run();

            try {
                if ($user) {
                    $user->image_small_id = $entity->image;
                    $user->image_middle_id = $entity->image;
                    $user->image_big_id = $entity->image;
                    $this->user->update($entity->id, $user);
                    Cache::tags(['user'])->flush();
                } else {
                    new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
                }
            } catch (Exception $error) {
                $this->user->destroy($entity->id);
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($entity);
    }
}
