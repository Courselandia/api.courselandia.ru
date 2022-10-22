<?php
/**
 * Основные посредники.
 *
 * @package App.Http.Middleware
 */

namespace App\Http\Middleware;

use DB;
use App;
use Closure;
use Config;
use Illuminate\Http\Request;

/**
 * Класс посредник для установки локалей.
 */
class Locale
{
    /**
     * Установка локалей.
     *
     * @param  Request  $request  Запрос.
     * @param  Closure  $next  Функция последующего действия.
     *
     * @return mixed Вернет результат продолжение запроса.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $locale = $request->get('locale', Config::get('app.locale'));

        if ($locale === 'en') {
            setlocale(LC_ALL, ['en_US.utf8']);
            setlocale(LC_NUMERIC, ['en_US.utf8']);

            if (Config::get('database.default') !== 'sqlite') {
                DB::statement("SET lc_time_names = 'en_US'");
            }
        } else {
            setlocale(LC_ALL, ['ru_RU.utf8', 'rus_RUS.utf8', 'russian']);
            setlocale(LC_NUMERIC, ['ru_RU.utf8']);

            if (Config::get('database.default') !== 'sqlite') {
                DB::statement("SET lc_time_names = 'ru_RU'");
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
