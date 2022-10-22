<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Http\Middleware;

use Config;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Класс посредник для проверки пользователя, что он авторизовался.
 */
class AllowUser
{
    /**
     * Проверка пользователя, что он является авторизованным.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     * @param  array|null  $params  Параметры доступа.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request Вернет результат продолжение запроса.
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$params
    ): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request {
        if (Gate::allows('user')) {
            return $next($request);
        }

        return $this->getError($request->ajax());
    }

    /**
     * Получить ошибку.
     *
     * @param  bool  $ajax  Определяет, является ли данный запрос AJAX запросом.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response Вернет ошибку.
     */
    private function getError(bool $ajax): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response
    {
        if ($ajax) {
            return response()->json([
                'success' => false,
                'message' => trans('access::http.middleware.allowUser.message')
            ])->setStatusCode(401);
        } elseif (Config::get('auth.redirections.login')) {
            return redirect(Config::get('auth.redirections.login'));
        } elseif (Config::get('auth.redirections.register')) {
            return redirect(Config::get('auth.redirections.login'));
        }

        return response(trans('access::http.middleware.allowUser.label'), 401);
    }
}
