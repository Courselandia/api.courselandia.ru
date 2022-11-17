<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Pipes\Site\SignIn;

use Geo;
use Log;
use Cache;
use Closure;
use Device;
use Request;
use Exception;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\Entities\AccessSignUp;
use App\Models\Entity;
use App\Models\Contracts\Pipe;
use App\Modules\Access\Entities\AccessSignIn;
use App\Modules\User\Models\UserAuth;
use App\Modules\User\Entities\UserAuth as UserAuthEntity;

/**
 * Авторизация пользователя: Запись об авторизации пользователя.
 */
class AuthPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param  Entity|AccessSignIn|AccessSignUp  $entity  Сущность.
     * @param  Closure  $next  Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Entity|AccessSignIn|AccessSignUp $entity, Closure $next): mixed
    {
        if (!$entity->two_factor) {
            $userAuth = new UserAuthEntity();
            $userAuth->user_id = $entity->id;
            $userAuth->os = Device::operationSystem();
            $userAuth->device = Device::system();
            $userAuth->browser = Device::browser();
            $userAuth->agent = Device::getAgent();
            $userAuth->ip = Request::ip();

            try {
                $location = Geo::get();

                $userAuth->latitude = $location->latitude;
                $userAuth->longitude = $location->longitude;
            } catch (Exception $error) {
                Log::alert('The GEO for the IP '.Request::ip().' is undetectable ('.$error->getMessage().').');
            }

            // UserAuth::create($userAuth->toArray());
            Cache::tags(['access', 'user'])->flush();
        }

        return $next($entity);
    }
}
