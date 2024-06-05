<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Providers;

use App;
use Config;
use Widget;
use App\Modules\Widget\Managers\WidgetManager;
use Illuminate\Support\ServiceProvider;
use App\Modules\Widget\Models\Widget as WidgetModel;
use App\Modules\Widget\Events\Listeners\WidgetListener;
use App\Modules\Widget\Widgets\CollectionsAlso;
use App\Modules\Widget\Widgets\PublicationsAlso;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class WidgetServiceProvider extends ServiceProvider
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
        $this->registerViews();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        WidgetModel::observe(WidgetListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('widget', function ($app) {
            return new WidgetManager($app);
        });

        Widget::extend('publications-also', function () {
            return new PublicationsAlso();
        });

        Widget::extend('collections-also', function () {
            return new CollectionsAlso();
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
            __DIR__ . '/../Config/config.php' => config_path('widget.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'widget'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/widget');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/widget';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'widget'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/widget');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'widget');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'widget');
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
