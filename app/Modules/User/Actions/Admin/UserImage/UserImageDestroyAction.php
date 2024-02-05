<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Actions\Admin\UserImage;

use App\Models\Exceptions\ParameterInvalidException;
use Cache;
use ImageStore;
use App\Models\Action;
use App\Modules\User\Actions\Admin\User\UserGetAction;
use App\Modules\User\Entities\User as UserEntity;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Exceptions\UserNotExistException;
use App\Modules\User\Models\User;
use ReflectionException;

/**
 * Удаление изображения пользователя.
 */
class UserImageDestroyAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID пользователей.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
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
            $action = new UserGetAction($this->id);
            $user = $action->run();

            if ($user) {
                if ($user->image_small_id) {
                    ImageStore::destroy($user->image_small_id->id);
                }
                if ($user->image_middle_id) {
                    ImageStore::destroy($user->image_middle_id->id);
                }
                if ($user->image_big_id) {
                    ImageStore::destroy($user->image_big_id->id);
                }

                $user->image_small_id = null;
                $user->image_middle_id = null;
                $user->image_big_id = null;

                User::find($this->id)->update($user->toArray());
                Cache::tags(['user'])->flush();

                return $user;
            }
        }

        throw new UserNotExistException(trans('access::http.actions.site.userConfigUpdateAction.notExistUser'));
    }
}
