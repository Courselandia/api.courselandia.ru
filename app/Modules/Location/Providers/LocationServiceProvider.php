<?php
/**
 * Модуль География.
 * Этот модуль содержит все классы для работы со странами, районами, городами и т.д.
 *
 * @package App\Modules\Location
 */

namespace App\Modules\Location\Providers;

use Config;
use App;
use Illuminate\Support\ServiceProvider;

use App\Modules\Location\Models\Location as LocationModel;
use App\Modules\Location\Contracts\Location as LocationContract;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class LocationServiceProvider extends ServiceProvider
{
    /**
     * Обработчик события загрузки приложения.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        App::singleton(LocationContract::class, function () {
            return new LocationModel();
        });
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('location.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'location'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/location');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/location';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'location'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/Access');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'location');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'location');
        }
    }

    /**
     * Получение сервисов через сервис-провайдер.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }
}
