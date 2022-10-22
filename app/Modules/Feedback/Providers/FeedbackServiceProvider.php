<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;
use App\Modules\Feedback\Models\Feedback as FeedbackModel;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;
use App\Modules\Feedback\Repositories\Feedback as FeedbackRepository;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class FeedbackServiceProvider extends ServiceProvider
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

        App::singleton(FeedbackRepository::class, function () {
            return new FeedbackRepository(new FeedbackModel(), new FeedbackEntity());
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
            __DIR__.'/../Config/config.php' => config_path('feedback.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'feedback'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/feedback');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/feedback';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'feedback'
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
            $this->loadTranslationsFrom($langPath, 'feedback');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'feedback');
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
