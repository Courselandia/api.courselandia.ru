<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use Morph;
use App\Models\Morph\PhpMorphy;
use Illuminate\Support\ServiceProvider;
use App\Models\Morph\MorphManager;

/**
 * Класс сервис-провайдера для морфирования.
 */
class MorphServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('morph', function ($app) {
            return new MorphManager($app);
        });

        Morph::extend('phpMorphy', function () {
            return new PhpMorphy();
        });
    }
}
