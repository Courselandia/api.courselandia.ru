<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Modules\Access\Entities\AccessSignUp;
use Cache;
use Exception;
use Closure;
use App\Models\Entity;
use App\Modules\Access\Entities\AccessSocial;
use App\Models\Contracts\Pipe;
use App\Modules\User\Repositories\User;
use App\Modules\User\Repositories\UserVerification;
use App\Modules\Access\Actions\Site\AccessSendEmailVerificationCodeAction;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;

/**
 * Регистрация нового пользователя: создания кода на верификации и его отправка пользователю.
 */
class VerificationPipe implements Pipe
{
    /**
     * Репозиторий пользователей.
     *
     * @var User
     */
    private User $user;

    /**
     * Репозиторий верификации пользователя.
     *
     * @var UserVerification
     */
    private UserVerification $userVerification;

    /**
     * Конструктор.
     *
     * @param  User  $user  Репозиторий пользователей.
     * @param  UserVerification  $userVerification  Репозиторий верификации пользователя.
     */
    public function __construct(User $user, UserVerification $userVerification)
    {
        $this->user = $user;
        $this->userVerification = $userVerification;
    }

    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSocial|AccessSignUp  $entity  Содержит массив свойств, которые можно передавать от pipe к pipe.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws Exception
     */
    public function handle(Entity|AccessSocial|AccessSignUp $entity, Closure $next): mixed
    {
        if ($entity->create) {
            $userVerificationEntity = new UserVerificationEntity();
            $userVerificationEntity->user_id = $entity->id;
            $userVerificationEntity->code = UserVerificationEntity::generateCode($entity->id);
            $userVerificationEntity->status = $entity->verified;

            $this->userVerification->create($userVerificationEntity);
            Cache::tags(['access', 'user'])->flush();

            try {
                if (!$entity->verified) {
                    try {
                        $action = app(AccessSendEmailVerificationCodeAction::class);
                        $action->id = $entity->id;
                        $action->run();
                    } catch (Exception $error) {
                        $this->user->destroy($entity->id);
                        Cache::tags(['access', 'user'])->flush();

                        throw $error;
                    }
                }
            } catch (Exception $error) {
                $this->user->destroy($entity->id);
                Cache::tags(['access', 'user'])->flush();

                throw $error;
            }
        }

        return $next($entity);
    }
}
