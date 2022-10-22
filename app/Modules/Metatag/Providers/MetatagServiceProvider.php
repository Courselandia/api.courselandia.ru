<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;
use App\Modules\Metatag\Models\Metatag as MetatagModel;
use App\Modules\Metatag\Entities\Metatag as MetatagEntity;
use App\Modules\Metatag\Repositories\Metatag as MetatagRepository;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class MetatagServiceProvider extends ServiceProvider
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

        App::singleton(MetatagRepository::class, function () {
            return new MetatagRepository(new MetatagModel(), new MetatagEntity());
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
            __DIR__.'/../Config/config.php' => config_path('metatag.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'metatag'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/metatag');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/metatag';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'metatag'
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
            $this->loadTranslationsFrom($langPath, 'metatag');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'metatag');
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
