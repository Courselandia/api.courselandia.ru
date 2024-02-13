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
use App\Modules\User\Entities\User as UserEntity;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;
use App\Modules\User\Models\UserVerification;
use Cache;
use Mail;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\User\Models\User;
use App\Modules\Access\Emails\Site\Verification;
use App\Models\Exceptions\UserNotExistException;
use Util;

/**
 * Отправка e-mail сообщения с кодом верификации.
 */
class AccessSendEmailVerificationCodeAction extends Action
{
    /**
     * ID пользователя.
     *
     * @var int|string
     */
    private int|string $id;

    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws UserNotExistException
     * @throws ParameterInvalidException
     * @throws UserVerifiedException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('access', 'user', $this->id, 'verification');

        $user = Cache::tags(['access', 'user'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $user = User::where('id', $this->id)
                    ->active()
                    ->with('verification')
                    ->first();

                return $user ? UserEntity::from($user->toArray()) : null;
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

                UserVerification::create($userVerificationEntity->toArray());

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
