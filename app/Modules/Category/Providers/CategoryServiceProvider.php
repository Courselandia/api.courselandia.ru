<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Category\Models\Category as CategoryModel;
use App\Modules\Category\Repositories\Category as CategoryRepository;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Events\Listeners\CategoryListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class CategoryServiceProvider extends ServiceProvider
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

        CategoryModel::observe(CategoryListener::class);
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
            CategoryRepository::class,
            function () {
                return new CategoryRepository(new CategoryModel(), new CategoryEntity());
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
            __DIR__.'/../Config/config.php' => config_path('category.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'category'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/category');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/category';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'category'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/category');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'category');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'category');
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
