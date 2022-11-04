<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Faq\Models\Faq as FaqModel;
use App\Modules\Faq\Repositories\Faq as FaqRepository;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Events\Listeners\FaqListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class FaqServiceProvider extends ServiceProvider
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

        FaqModel::observe(FaqListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        App::singleton(
            FaqRepository::class,
            function () {
                return new FaqRepository(new FaqModel(), new FaqEntity());
            }
        );
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('faq.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'faq'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/faq');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/faq';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'faq'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/faq');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'faq');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'faq');
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
