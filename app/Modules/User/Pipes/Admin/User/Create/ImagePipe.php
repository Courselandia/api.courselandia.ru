<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Create;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Entity;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Data\Decorators\UserCreate;
use App\Modules\User\Models\User;
use Cache;
use Closure;
use Exception;

/**
 * Создание пользователя: добавление изображения пользователя.
 */
class ImagePipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|UserCreate $data Данные для декоратора создания пользователя.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws Exception
     */
    public function handle(Data|UserCreate $data, Closure $next): mixed
    {
        if ($data->image) {
            try {
                $action = new UserGetAction($data->id);
                $user = $action->run();

                if ($user) {
                    $user = $user->toArray();
                    $user['image_small_id'] = $data->image;
                    $user['image_middle_id'] = $data->image;
                    $user['image_big_id'] = $data->image;

                    User::find($data->id)->update($user);
                    Cache::tags(['user'])->flush();
                } else {
                    new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
                }
            } catch (Exception $error) {
                User::destroy($data->id);
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($data);
    }
}
