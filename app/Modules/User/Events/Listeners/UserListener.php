<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Events\Listeners;

use App\Models\Exceptions\UserExistException;
use App\Modules\User\Models\User;
use ImageStore;

/**
 * Класс обработчик событий для модели пользователей.
 */
class UserListener
{
    /**
     * Обработчик события при добавлении записи.
     *
     * @param User $user Модель для таблицы пользователей.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws UserExistException
     */
    public function creating(User $user): bool
    {
        $result = $user->newQuery()
            ->where('login', $user->login)
            ->first();

        if ($result) {
            throw new UserExistException(trans('user::events.listeners.userListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при обновлении записи.
     *
     * @param User $user Модель для таблицы пользователей.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws UserExistException
     */
    public function updating(User $user): bool
    {
        $result = $user->newQuery()
            ->where('id', '!=', $user->id)
            ->where('login', $user->login)
            ->first();

        if ($result) {
            throw new UserExistException(trans('user::events.listeners.userListener.existError'));
        }

        return true;
    }

    /**
     * Обработчик события при удалении записи.
     *
     * @param User $user Модель для таблицы пользователей.
     *
     * @return bool Вернет успешность выполнения операции.
     */
    public function deleting(User $user): bool
    {
        if ($user->image_small_id) {
            ImageStore::destroy($user->image_small_id['id']);
        }
        if ($user->image_middle_id) {
            ImageStore::destroy($user->image_middle_id['id']);
        }
        if ($user->image_big_id) {
            ImageStore::destroy($user->image_big_id['id']);
        }

        $user->deleteRelation($user->verification(), $user->isForceDeleting());
        $user->deleteRelation($user->recovery(), $user->isForceDeleting());
        $user->deleteRelation($user->auths(), $user->isForceDeleting());
        $user->deleteRelation($user->role(), $user->isForceDeleting());
        $user->deleteRelation($user->tasks(), $user->isForceDeleting());

        return true;
    }
}
