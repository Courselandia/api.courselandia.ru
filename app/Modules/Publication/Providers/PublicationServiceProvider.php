<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Publication\Models\Publication as PublicationModel;
use App\Modules\Publication\Repositories\Publication as PublicationRepository;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Events\Listeners\PublicationListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class PublicationServiceProvider extends ServiceProvider
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

        PublicationModel::observe(PublicationListener::class);
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
            PublicationRepository::class,
            function () {
                return new PublicationRepository(new PublicationModel(), new PublicationEntity());
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
            __DIR__.'/../Config/config.php' => config_path('publication.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'publication'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/publication');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/publication';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'publication'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/publication');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'publication');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'publication');
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
