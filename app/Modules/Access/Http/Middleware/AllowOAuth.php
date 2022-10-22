<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 *
 * @package App\Modules\Access
 */

namespace App\Modules\Access\Http\Middleware;

use Config;
use Closure;
use OAuth;
use Auth;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;

/**
 * Класс посредник для проверки аутентификации через API.
 */
class AllowOAuth
{
    /**
     * Проверка пользователя, что он авторизован через API.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response
    {
        $header = $request->header('Authorization') ? $request->header('Authorization') : $request->bearerToken();

        if ($header) {
            $token = str_replace('Bearer ', '', $header);

            try {
                OAuth::check($token);

                $data = OAuth::decode($token, 'accessToken');
                Auth::loginUsingId($data->user);

                return $next($request);
            } catch (Exception $error) {
                return $this->getError($request->ajax(), $error->getMessage());
            }
        }

        return $this->getError($request->ajax(), trans('access::http.middleware.allowOAuth.noHeader'));
    }

    /**
     * Получить ошибку.
     *
     * @param  bool  $ajax  Определяет, является ли данный запрос AJAX запросом.
     * @param  string|null  $message  Сообщение об ошибки.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response Вернет ошибку.
     */
    private function getError(
        bool $ajax,
        string $message = null
    ): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response {
        if ($ajax) {
            return response()->json([
                'success' => false,
                'message' => $message
            ])->setStatusCode(401);
        } elseif (Config::get('auth.redirections.login')) {
            return redirect(Config::get('auth.redirections.login'));
        } elseif (Config::get('auth.redirections.register')) {
            return redirect(Config::get('auth.redirections.login'));
        }

        return response($message, 401);
    }
}
