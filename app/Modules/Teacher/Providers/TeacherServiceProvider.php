<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Providers;

use App\Modules\Teacher\Commands\TeacherNormalizeCommand;
use App\Modules\Teacher\Commands\UploadPhotosCommand;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Teacher\Models\Teacher as TeacherModel;
use App\Modules\Teacher\Events\Listeners\TeacherListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class TeacherServiceProvider extends ServiceProvider
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

        TeacherModel::observe(TeacherListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            UploadPhotosCommand::class,
            TeacherNormalizeCommand::class,
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
            __DIR__.'/../Config/config.php' => config_path('teacher.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'teacher'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/teacher');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/teacher';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'teacher'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/teacher');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'teacher');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'teacher');
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
