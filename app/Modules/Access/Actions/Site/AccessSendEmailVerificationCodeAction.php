<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Actions\Site;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\UserVerifiedException;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;
use App\Modules\User\Repositories\UserVerification;
use Cache;
use Mail;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\User\Repositories\User;
use App\Modules\Access\Emails\Site\Verification;
use App\Models\Exceptions\UserNotExistException;
use ReflectionException;
use Util;

/**
 * Отправка e-mail сообщения с кодом верификации.
 */
class AccessSendEmailVerificationCodeAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException|ReflectionException
     * @throws UserVerifiedException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setActive(true)
            ->addRelation('verification');

        $cacheKey = Util::getKey('access', 'user', $query);

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->user->get($query);
            }
        );

        if ($user) {
            if ($user->verification?->status === true) {
                throw new UserVerifiedException(
                    trans('access::actions.site.accessSendEmailVerificationCodeAction.verified')
                );
            }

            if (!$user->verification) {
                $userVerificationEntity = new UserVerificationEntity();
                $userVerificationEntity->user_id = $this->id;
                $userVerificationEntity->code = UserVerificationEntity::generateCode($this->id);
                $userVerificationEntity->status = false;

                $this->userVerification->create($userVerificationEntity);
                $code = $userVerificationEntity->code;
                Cache::tags(['access', 'user'])->flush();
            } else {
                $code = $user->verification->code;
            }

            Mail::to($user->login)->queue(new Verification($user, $code));

            return true;
        }

        throw new UserNotExistException(
            trans('access::actions.site.accessSendEmailVerificationCodeAction.notExistUser')
        );
    }
}
