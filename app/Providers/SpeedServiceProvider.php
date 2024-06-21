<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use App\Models\Speed;
use Illuminate\Support\ServiceProvider;

/**
 * Класс сервис-провайдера для модуля измерения скорости.
 */
class SpeedServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton(
            'speed',
            function () {
                return new Speed();
            }
        );
    }
}
