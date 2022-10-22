<?php
/**
 * Основные посредники.
 *
 * @package App.Http.Middleware
 */

namespace App\Http\Middleware;

use Closure;
use Config;
use Illuminate\Http\Request;

/**
 * Класс посредник для проверки идет ли этот запрос через AJAX.
 */
class AllowOnlyAjaxRequests
{
    /**
     * Проверка запроса, который пришел как AJAX.
     * Если в конфигурации app.env установлено 'local', то запрос пройдет в любом случаи,
     * т.к. считается что система работает в тестовом режиме и проводить жесткий контроль
     * запроса, не требуется.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     *
     * @return mixed Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->ajax() === false && Config::get('app.env', 'local') === 'production') {
            return response('No allowed!', 405);
        }

        return $next($request);
    }
}
