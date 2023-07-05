<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Providers;

use App;
use App\Modules\Article\Commands\ArticleRewriteCommand;
use Config;
use ArticleCategory;
use Illuminate\Support\ServiceProvider;
use App\Modules\Article\Commands\ArticleApplyCommand;
use App\Modules\Article\Commands\ArticleWriteCommand;
use App\Modules\Article\Categories\ArticleCategoryManager;
use App\Modules\Article\Categories\CourseTextArticleCategory;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class ArticleServiceProvider extends ServiceProvider
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
        App::singleton('articleCategory', function ($app) {
            return new ArticleCategoryManager($app);
        });

        ArticleCategory::extend('course.text', function () {
            return new CourseTextArticleCategory();
        });

        $this->commands([
            ArticleWriteCommand::class,
            ArticleApplyCommand::class,
            ArticleRewriteCommand::class,
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
            __DIR__.'/../Config/config.php' => config_path('article.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'article'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/article');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/article';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'article'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/article');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'article');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'article');
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
