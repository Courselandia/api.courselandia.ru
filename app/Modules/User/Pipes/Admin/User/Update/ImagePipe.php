<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Pipes\Admin\User\Update;

use App\Models\Contracts\Pipe;
use App\Models\Data;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Data\Decorators\UserProfileUpdate;
use App\Modules\User\Data\Decorators\UserUpdate;
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
     * @param Data|UserUpdate|UserProfileUpdate $data Данные для декоратора обновления.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws Exception
     */
    public function handle(Data|UserUpdate|UserProfileUpdate $data, Closure $next): mixed
    {
        if ($data->image) {
            $action = new UserGetAction($data->id);
            $user = $action->run();

            try {
                if ($user) {
                    $dataUpdate = [
                        ...$user->toArray(),
                        'image_small_id' => $data->image,
                        'image_middle_id' => $data->image,
                        'image_big_id' => $data->image,
                    ];

                    User::find($data->id)->update($dataUpdate);
                    Cache::tags(['user'])->flush();
                } else {
                    new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
                }
            } catch (Exception $error) {
                Cache::tags(['user'])->flush();

                throw $error;
            }
        }

        return $next($data);
    }
}
