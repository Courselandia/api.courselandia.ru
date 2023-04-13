<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Providers;

use App\Modules\Course\Commands\CourseExportCommand;
use App\Modules\Course\Commands\CourseFillCommand;
use App\Modules\Course\Commands\CourseImportCommand;
use Config;
use Illuminate\Support\ServiceProvider;
use App\Modules\Course\Models\Course as CourseModel;
use App\Modules\Course\Events\Listeners\CourseListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class CourseServiceProvider extends ServiceProvider
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

        CourseModel::observe(CourseListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            CourseImportCommand::class,
            CourseFillCommand::class,
            CourseExportCommand::class,
        ]);
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('course.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'course'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/course');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/course';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'course'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/course');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'course');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'course');
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
