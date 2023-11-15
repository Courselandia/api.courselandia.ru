<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Providers;

use App;
use App\Modules\Analyzer\Categories\ArticleTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\CategoryTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\DirectionTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\ProfessionTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\SchoolTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\SkillTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\TeacherTextAnalyzerCategory;
use App\Modules\Analyzer\Categories\ToolTextAnalyzerCategory;
use App\Modules\Analyzer\Commands\AnalyzerShiftToCourseTextCommand;
use Config;
use AnalyzerCategory;
use Illuminate\Support\ServiceProvider;
use App\Modules\Analyzer\Commands\AnalyzerAnalyzeCommand;
use App\Modules\Analyzer\Categories\AnalyzerCategoryManager;
use App\Modules\Analyzer\Categories\CourseTextAnalyzerCategory;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class AnalyzerServiceProvider extends ServiceProvider
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

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('analyzerCategory', function ($app) {
            return new AnalyzerCategoryManager($app);
        });

        AnalyzerCategory::extend('course.text', function () {
            return new CourseTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('article.text', function () {
            return new ArticleTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('skill.text', function () {
            return new SkillTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('tool.text', function () {
            return new ToolTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('direction.text', function () {
            return new DirectionTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('profession.text', function () {
            return new ProfessionTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('category.text', function () {
            return new CategoryTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('school.text', function () {
            return new SchoolTextAnalyzerCategory();
        });

        AnalyzerCategory::extend('teacher.text', function () {
            return new TeacherTextAnalyzerCategory();
        });

        $this->commands([
            AnalyzerAnalyzeCommand::class,
            AnalyzerShiftToCourseTextCommand::class,
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
            __DIR__ . '/../Config/config.php' => config_path('analyzer.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'analyzer'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/analyzer');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/analyzer';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'analyzer'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/analyzer');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'analyzer');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'analyzer');
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
