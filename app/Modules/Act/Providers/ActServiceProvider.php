<?php
/**
 * Модуль Запоминания действий.
 * Этот модуль содержит все классы для работы с запоминанием и контролем действий пользователя.
 *
 * @package App\Modules\Act
 */

namespace App\Modules\Act\Providers;

use Config;
use App;
use Illuminate\Support\ServiceProvider;

use App\Modules\Act\Models\Implement as Implement;
use App\Modules\Act\Models\Act as ActModel;
use App\Modules\Act\Entities\Act as ActEntity;
use App\Modules\Act\Repositories\Act as RepositoryAct;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class ActServiceProvider extends ServiceProvider
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

        App::singleton(RepositoryAct::class, function () {
            return new RepositoryAct(new ActModel(), new ActEntity());
        });

        App::bind('act', function () {
            return app(Implement::class);
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
            __DIR__.'/../Config/config.php' => config_path('act.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'act'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/act');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/act';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'act'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/act');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'act');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'act');
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
