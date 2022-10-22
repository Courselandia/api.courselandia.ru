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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Gate;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Класс посредник для проверки пользователя, что он верифицирован или не верифицирован.
 */
class AllowVerified
{
    /**
     * Проверка пользователя.
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
        $status = $params[0] ?? true;

        if (Gate::allows('verified', $status)) {
            return $next($request);
        }

        return $this->getError($request->ajax(), $status);
    }

    /**
     * Получить ошибку.
     *
     * @param  bool  $ajax  Определяет, является ли данный запрос AJAX запросом.
     * @param  bool  $status  Если указан true, то проверить что пользователь верифицирован, если false, то не верифицирован.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response Вернет ошибку.
     */
    private function getError(
        bool $ajax,
        bool $status = true
    ): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response {
        if ($ajax) {
            return response()->json([
                'success' => false,
                'message' => $status ? trans('access::http.middleware.allowVerified.message.true') : trans(
                    'access::http.middleware.allowVerified.message.false'
                )
            ])->setStatusCode(401);
        } elseif (Config::get('auth.redirections.verify') && $status) {
            return redirect(Config::get('auth.redirections.verify'));
        } elseif (Config::get('auth.redirections.unverify') && !$status) {
            return redirect(Config::get('auth.redirections.unverified'));
        }

        return response(
            $status ? trans('access::http.middleware.allowVerified.label.true') : trans(
                'access::http.middleware.allowVerified.label.false'
            ),
            401
        );
    }
}
