<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Providers;

use App;
use Config;
use App\Modules\Log\Entities\Log as LogEntity;
use App\Modules\Log\Models\LogMongoDb as LogMongoDbModel;
use App\Modules\Log\Repositories\Log as LogRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class LogServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        // $this->registerConfig();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton(LogRepository::class, function () {
            return new LogRepository(new LogMongoDbModel(), LogEntity::class);
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
            __DIR__ . '/../Config/config.php' => config_path('log.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'log'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/log');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/log';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'log'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/log');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'log');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'log');
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
