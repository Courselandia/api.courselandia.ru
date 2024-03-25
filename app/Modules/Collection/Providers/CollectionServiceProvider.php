<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Providers;

use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Collection\Models\Collection as CollectionModel;
use App\Modules\Collection\Events\Listeners\CollectionListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class CollectionServiceProvider extends ServiceProvider
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

        CollectionModel::observe(CollectionListener::class);
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
            __DIR__ . '/../Config/config.php' => config_path('collection.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'collection'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/collection');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/collection';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'collection'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/collection');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'collection');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'collection');
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
