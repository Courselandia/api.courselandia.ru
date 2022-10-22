<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Providers;

use Config;
use App;
use DocumentStore;

use Illuminate\Support\ServiceProvider;

use App\Modules\Document\Models\DocumentEloquent as DocumentEloquentModel;
use App\Modules\Document\Repositories\DocumentEloquent;

use App\Modules\Document\Models\DocumentMongoDb as DocumentMongoDbModel;
use App\Modules\Document\Repositories\DocumentMongoDb;

use App\Modules\Document\Events\Listeners\DocumentListener;

use App\Modules\Document\Models\DocumentManager;
use App\Modules\Document\Models\DocumentDriverManager;
use App\Modules\Document\Models\DocumentDriverBase;
use App\Modules\Document\Models\DocumentDriverFtp;
use App\Modules\Document\Models\DocumentDriverHttp;
use App\Modules\Document\Models\DocumentDriverLocal;

use App\Modules\Document\Entities\Document;

use App\Modules\Document\Commands\DocumentMigrateCommand;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class DocumentServiceProvider extends ServiceProvider
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

        DocumentEloquentModel::observe(DocumentListener::class);
        DocumentMongoDbModel::observe(DocumentListener::class);
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->commands([
            DocumentMigrateCommand::class,
        ]);

        App::singleton('document.store', function ($app) {
            return new DocumentManager($app);
        });

        DocumentStore::extend('database', function () {
            return new DocumentEloquent(new DocumentEloquentModel(), new Document());
        });

        DocumentStore::extend('mongodb', function () {
            return new DocumentMongoDb(new DocumentMongoDbModel(), new Document());
        });

        App::singleton('document.store.driver', function ($app) {
            return new DocumentDriverManager($app);
        });

        App::make('document.store.driver')->extend('base', function () {
            return new DocumentDriverBase();
        });

        App::make('document.store.driver')->extend('ftp', function () {
            return new DocumentDriverFtp();
        });

        App::make('document.store.driver')->extend('local', function () {
            return new DocumentDriverLocal();
        });

        App::make('document.store.driver')->extend('http', function () {
            return new DocumentDriverHttp();
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
            __DIR__.'/../Config/config.php' => config_path('document.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'document'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/document');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/document';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'document'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/document');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'document');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'document');
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
