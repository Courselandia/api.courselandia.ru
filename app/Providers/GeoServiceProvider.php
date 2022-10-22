<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use App\Models\Geo\IpApi;
use Geo;
use Illuminate\Support\ServiceProvider;
use App\Models\Geo\GeoManager;

/**
 * Класс сервис-провайдера для геопозиционирования.
 */
class GeoServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('geo', function ($app) {
            return new GeoManager($app);
        });

        Geo::extend('ipApi', function () {
            return new IpApi();
        });
    }
}
