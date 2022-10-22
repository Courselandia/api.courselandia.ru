<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use Currency;
use Illuminate\Support\ServiceProvider;
use App\Models\Currency\CurrencyManager;
use App\Models\Currency\CurrencyCbr;

/**
 * Класс сервис-провайдера для валют.
 */
class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('currency', function ($app) {
            return new CurrencyManager($app);
        });

        Currency::extend('cbr', function () {
            return new CurrencyCbr();
        });
    }
}
