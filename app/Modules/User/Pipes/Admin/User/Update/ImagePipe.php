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
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\UserUpdate;
use App\Modules\User\Models\User;
use Cache;
use Closure;
use Exception;

/**
 * Обновление пользователя: добавление изображения пользователя.
 */
class ImagePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|UserUpdate $entity Сущность для создания пользователя.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     * @throws Exception
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

                    User::find($entity->id)->update($user->toArray());
                    Cache::tags(['user'])->flush();
                } else {
                    new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
                }
            } catch (Exception $error) {
                User::destroy($entity->id);
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($entity);
    }
}
