<?php
/**
 * Модуль промоматериалов.
 * Этот модуль содержит все классы для работы с промоматериалами: промокоды и промоакции.
 *
 * @package App\Modules\Promo
 */

namespace App\Modules\Promo\Providers;

use Config;
use Illuminate\Support\ServiceProvider;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class PromoServiceProvider extends ServiceProvider
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

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {

    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('promo.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'promo'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/promo');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/promo';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'promo'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/promo');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'promo');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'promo');
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
