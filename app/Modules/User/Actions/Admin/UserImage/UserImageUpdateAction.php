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
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Repositories\User;
use Cache;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Обновление изображения пользователя.
 */
class UserImageUpdateAction extends Action
{
    /**
     * Репозиторий пользователя.
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
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

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
     * @return UserEntity Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
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
                $this->user->update($this->id, $user);
                Cache::tags(['user'])->flush();

                return $action->run();
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
    }
}
