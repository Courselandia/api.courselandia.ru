<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Providers;

use App;
use Config;
use Plagiarism;
use App\Modules\Plagiarism\Models\PlagiarismFake;
use App\Modules\Plagiarism\Models\PlagiarismTextRu;
use App\Modules\Plagiarism\Models\PlagiarismManager;
use Illuminate\Support\ServiceProvider;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class PlagiarismServiceProvider extends ServiceProvider
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
        App::singleton('plagiarism', function ($app) {
            return new PlagiarismManager($app);
        });

        Plagiarism::extend('textRu', function () {
            return new PlagiarismTextRu();
        });

        Plagiarism::extend('fake', function () {
            return new PlagiarismFake();
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
            __DIR__.'/../Config/config.php' => config_path('plagiarism.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'plagiarism'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/plagiarism');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/plagiarism';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'plagiarism'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/plagiarism');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'plagiarism');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'plagiarism');
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
