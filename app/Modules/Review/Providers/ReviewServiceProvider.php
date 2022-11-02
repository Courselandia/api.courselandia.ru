<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Review\Models\Review as ReviewModel;
use App\Modules\Review\Repositories\Review as ReviewRepository;
use App\Modules\Review\Entities\Review as ReviewEntity;
use App\Modules\Review\Events\Listeners\ReviewListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class ReviewServiceProvider extends ServiceProvider
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

        ReviewModel::observe(ReviewListener::class);
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
            ReviewRepository::class,
            function () {
                return new ReviewRepository(new ReviewModel(), new ReviewEntity());
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
            __DIR__.'/../Config/config.php' => config_path('review.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'review'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/review');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/review';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'review'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/review');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'review');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'review');
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
