<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignUp;

use App\Models\Data;
use Cache;
use Exception;
use Closure;
use App\Modules\Access\Data\Decorators\AccessSocial;
use App\Modules\Access\Data\Decorators\AccessSignUp;
use App\Models\Contracts\Pipe;
use App\Modules\User\Models\User;
use App\Modules\User\Models\UserVerification;
use App\Modules\Access\Actions\Site\AccessSendEmailVerificationCodeAction;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;

/**
 * Регистрация нового пользователя: создания кода на верификации и его отправка пользователю.
 */
class VerificationPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|AccessSocial|AccessSignUp $data Данные.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws Exception
     */
    public function handle(Data|AccessSocial|AccessSignUp $data, Closure $next): mixed
    {
        if ($data->create) {
            UserVerification::create([
                'user_id' => $data->id,
                'code' => UserVerificationEntity::generateCode($data->id),
                'status' => $data->verified,
            ]);

            Cache::tags(['access', 'user'])->flush();

            try {
                if (!$data->verified) {
                    try {
                        $action = new AccessSendEmailVerificationCodeAction($data->id);
                        $action->run();
                    } catch (Exception $error) {
                        User::destroy($data->id);
                        Cache::tags(['access', 'user'])->flush();

                        throw $error;
                    }
                }
            } catch (Exception $error) {
                User::destroy($data->id);
                Cache::tags(['access', 'user'])->flush();

                throw $error;
            }
        }

        return $next($data);
    }
}
