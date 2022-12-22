<?php
/**
 * Основные посредники.
 *
 * @package App.Http.Middleware
 */

namespace App\Http\Middleware;

use Config;
use Closure;
use Illuminate\Http\Request;

/**
 * Класс посредник для аутентификации CKFinder.
 */
class CKFinderAuth
{
    /**
     * Проведем проверку аутентификации.
     *
     * @param Request $request Запрос.
     * @param Closure $next Функция последующего действия.
     *
     * @return mixed Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        config([
            'ckfinder.authentication' => function () use ($request) {
                return $request->get('_key') === Config::get('ckfinder.key');
            }
        ]);

        return $next($request);
    }
}
