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
use App\Models\DTO;
use App\Models\Entity;
use App\Models\Contracts\Pipe;
use App\Modules\User\Models\UserAuth;
use App\Modules\Access\DTO\Decorators\AccessSocial;
use App\Modules\Access\DTO\Decorators\AccessSignUp;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Access\DTO\Decorators\AccessSignIn;

/**
 * Авторизация пользователя: Запись об авторизации пользователя.
 */
class AuthPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|AccessSocial|AccessSignUp|AccessSignIn $data DTO.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(DTO|AccessSocial|AccessSignUp|AccessSignIn $data, Closure $next): mixed
    {
        if (!$data->user->two_factor) {
            $userAuthData = [
                'user_id' => $data->id,
                'os' => Device::operationSystem(),
                'device' => Device::system(),
                'browser' => Device::browser(),
                'agent' => Device::getAgent(),
                'ip' => Request::ip(),
            ];

            try {
                $location = Geo::get();

                $userAuthData['latitude'] = $location->latitude;
                $userAuthData['longitude'] = $location->longitude;
            } catch (Exception $error) {
                Log::alert('The GEO for the IP ' . Request::ip() . ' is undetectable (' . $error->getMessage() . ').');
            }

            UserAuth::create($userAuthData);
            Cache::tags(['access', 'user'])->flush();
        }

        return $next($data);
    }
}
