<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Providers;

use Config;
use App;
use ImageStore;
use Illuminate\Support\ServiceProvider;
use App\Modules\Image\Models\ImageEloquent as ImageEloquentModel;
use App\Modules\Image\Repositories\ImageEloquent;
use App\Modules\Image\Models\ImageMongoDb as ImageMongoDbModel;
use App\Modules\Image\Repositories\ImageMongoDb;
use App\Modules\Image\Events\Listeners\ImageListener;
use App\Modules\Image\Models\ImageManager;
use App\Modules\Image\Models\ImageDriverManager;
use App\Modules\Image\Models\ImageDriverBase;
use App\Modules\Image\Models\ImageDriverFtp;
use App\Modules\Image\Models\ImageDriverHttp;
use App\Modules\Image\Models\ImageDriverLocal;
use App\Modules\Image\Entities\Image;
use App\Modules\Image\Commands\ImageMigrateCommand;
use App\Modules\Image\Commands\ImageNormalizeCommand;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class ImageServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        ImageEloquentModel::observe(ImageListener::class);
        ImageMongoDbModel::observe(ImageListener::class);
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            ImageMigrateCommand::class,
            ImageNormalizeCommand::class,
        ]);

        App::singleton('image.store', function ($app) {
            return new ImageManager($app);
        });

        ImageStore::extend('database', function () {
            return new ImageEloquent(new ImageEloquentModel(), Image::class);
        });

        ImageStore::extend('mongodb', function () {
            return new ImageMongoDb(new ImageMongoDbModel(), Image::class);
        });

        App::singleton('image.store.driver', function ($app) {
            return new ImageDriverManager($app);
        });

        App::make('image.store.driver')->extend('base', function () {
            return new ImageDriverBase();
        });

        App::make('image.store.driver')->extend('ftp', function () {
            return new ImageDriverFtp();
        });

        App::make('image.store.driver')->extend('local', function () {
            return new ImageDriverLocal();
        });

        App::make('image.store.driver')->extend('http', function () {
            return new ImageDriverHttp();
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
            __DIR__.'/../Config/config.php' => config_path('image.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'image'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/image');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/image';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'image'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/image');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'image');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'image');
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
