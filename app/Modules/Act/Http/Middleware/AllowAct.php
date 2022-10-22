<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Http\Middleware;

use Act;
use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

/**
 * Класс посредник для проверки возможности действия по количеству.
 */
class AllowAct
{
    /**
     * Проверка пользователя, что он делает действия допустимое количество раз.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     * @param  array|null  $params  Параметры доступа.
     *
     * @return ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next, ...$params): ResponseFactory|RedirectResponse|Application|Redirector|JsonResponse|Response|Request
    {
        $status = Act::status($params[0], $params[1], $params[2] ?? 60);

        if ($status) {
            return $next($request);
        }

        return $this->getError($request->ajax());
    }

    /**
     * Получить ошибку.
     *
     * @param  bool  $ajax  Определяет являться ли данный запрос AJAX запросом.
     *
     * @return mixed Вернет ошибку.
     */
    private function getError(bool $ajax): mixed
    {
        if ($ajax) {
            return response()->json([
                'success' => false,
                'message' => trans('act::http.middleware.allowAct.message')
            ])->setStatusCode(401);
        }

        return response(trans('act::http.middleware.allowAct.label'), 401);
    }
}
