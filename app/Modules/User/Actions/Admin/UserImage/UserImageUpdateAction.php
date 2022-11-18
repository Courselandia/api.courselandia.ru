<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\UserImage;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Models\User;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения пользователя.
 */
class UserImageUpdateAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Метод запуска логики.
     *
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): UserEntity
    {
        if ($this->id) {
            $action = app(UserGetAction::class);
            $action->id = $this->id;
            $user = $action->run();

            if ($user) {
                $user->image_small_id = $this->image;
                $user->image_middle_id = $this->image;
                $user->image_big_id = $this->image;

                User::find($this->id)->update($user->toArray());
                Cache::tags(['user'])->flush();

                return $action->run();
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
    }
}
