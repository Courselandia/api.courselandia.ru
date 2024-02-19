<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Providers;

use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Section\Models\Section as SectionModel;
use App\Modules\Section\Events\Listeners\SectionListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class SectionServiceProvider extends ServiceProvider
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

        SectionModel::observe(SectionListener::class);
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
            __DIR__ . '/../Config/config.php' => config_path('section.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'section'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/section');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/section';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'section'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/section');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'section');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'section');
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
