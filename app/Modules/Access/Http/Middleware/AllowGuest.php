<?php
/**
 * Модуль Авторизации и аутентификации.
 * Этот модуль содержит все классы для работы с авторизацией и аутентификации.
 */

namespace App\Modules\Access\Http\Middleware;

use Config;
use Closure;
use Gate;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Класс посредник для проверки пользователя, что он является гостем.
 */
class AllowGuest
{
    /**
     * Проверка пользователя, что он является не авторизовался.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request
    {
        if (!Gate::allows('user')) {
            return $next($request);
        } elseif ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => trans('access::http.middleware.allowGuest.text'),
            ])->setStatusCode(401);
        } elseif (Config::get('auth.redirections.unregister')) {
            return redirect(Config::get('auth.redirections.unregister'));
        }

        return response(trans('access::http.middleware.allowGuest.label'), 401);
    }
}
