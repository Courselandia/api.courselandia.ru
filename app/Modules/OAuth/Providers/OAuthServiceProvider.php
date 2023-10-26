<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Providers;

use Config;
use App;
use Illuminate\Support\ServiceProvider;

use App\Modules\OAuth\Models\OAuthTokenEloquent as ModelOAuthTokenEloquent;
use App\Modules\OAuth\Repositories\OAuthTokenEloquent as RepositoryOAuthTokenEloquent;
use App\Modules\OAuth\Events\Listeners\OAuthTokenEloquentListener;
use App\Modules\OAuth\Models\OAuthRefreshTokenEloquent as ModelOAuthRefreshTokenEloquent;
use App\Modules\OAuth\Repositories\OAuthRefreshTokenEloquent as RepositoryOAuthRefreshTokenEloquent;
use App\Modules\OAuth\Events\Listeners\OAuthRefreshTokenEloquentListener;
use App\Modules\OAuth\Entities\OAuthRefresh;
use App\Modules\OAuth\Entities\OAuthToken;
use App\Modules\OAuth\Models\OAuthDriverManager;
use App\Modules\OAuth\Models\OAuthDriverDatabase;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class OAuthServiceProvider extends ServiceProvider
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

        ModelOAuthTokenEloquent::observe(OAuthTokenEloquentListener::class);
        ModelOAuthRefreshTokenEloquent::observe(OAuthRefreshTokenEloquentListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton(RepositoryOAuthTokenEloquent::class, function () {
            return new RepositoryOAuthTokenEloquent(new ModelOAuthTokenEloquent(), new OAuthToken());
        });

        //

        App::singleton(RepositoryOAuthRefreshTokenEloquent::class, function () {
            return new RepositoryOAuthRefreshTokenEloquent(new ModelOAuthRefreshTokenEloquent(), new OAuthRefresh());
        });

        //

        App::singleton(
            'oauth',
            function ($app) {
                return new OAuthDriverManager($app);
            }
        );

        App::make('oauth')->extend(
            'database',
            function () {
                return new OAuthDriverDatabase(
                    app(RepositoryOAuthTokenEloquent::class),
                    app(RepositoryOAuthRefreshTokenEloquent::class)
                );
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
            __DIR__ . '/../Config/config.php' => config_path('oauth.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'oauth'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/oauth');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/oauth';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'oauth'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/oauth');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'oauth');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'oauth');
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
