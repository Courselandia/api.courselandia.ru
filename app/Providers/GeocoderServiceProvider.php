<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use Geocoder;
use Illuminate\Support\ServiceProvider;
use App\Models\Geocoder\GeocoderManager;
use App\Models\Geocoder\GeocoderGoogle;

/**
 * Класс сервис-провайдера для геокодирования.
 */
class GeocoderServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('geocoder', function ($app) {
            return new GeocoderManager($app);
        });

        Geocoder::extend('google', function () {
            return new GeocoderGoogle();
        });
    }
}
