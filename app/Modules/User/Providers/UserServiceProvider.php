<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Providers;

use App;
use Config;
use App\Modules\User\Repositories\UserVerification;
use Illuminate\Support\ServiceProvider;

use App\Modules\User\Models\User as UserModel;
use App\Modules\User\Repositories\User as UserRepository;
use App\Modules\User\Events\Listeners\UserListener;
use App\Modules\User\Entities\User as UserEntity;

use App\Modules\User\Models\UserAuth as UserAuthModel;
use App\Modules\User\Repositories\UserAuth as UserAuthRepository;
use App\Modules\User\Entities\UserAuth as UserAuthEntity;

use App\Modules\User\Models\UserRole as UserRoleModel;
use App\Modules\User\Repositories\UserRole as UserRoleRepository;
use App\Modules\User\Entities\UserRole as UserRoleEntity;

use App\Modules\User\Models\UserVerification as UserVerificationModel;
use App\Modules\User\Repositories\UserVerification as UserVerificationRepository;
use App\Modules\User\Entities\UserVerification as UserVerificationEntity;

use App\Modules\User\Models\UserRecovery as UserRecoveryModel;
use App\Modules\User\Repositories\UserRecovery as UserRecoveryRepository;
use App\Modules\User\Entities\UserRecovery as UserRecoveryEntity;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class UserServiceProvider extends ServiceProvider
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

        UserModel::observe(UserListener::class);
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
            UserRepository::class,
            function () {
                return new UserRepository(new UserModel(), new UserEntity());
            }
        );

        App::singleton(
            UserAuthRepository::class,
            function () {
                return new UserAuthRepository(new UserAuthModel(), new UserAuthEntity());
            }
        );

        App::singleton(
            UserRoleRepository::class,
            function () {
                return new UserRoleRepository(new UserRoleModel(), new UserRoleEntity());
            }
        );

        App::singleton(
            UserVerificationRepository::class,
            function () {
                return new UserVerificationRepository(new UserVerificationModel(), new UserVerificationEntity());
            }
        );

        App::singleton(
            UserRecoveryRepository::class,
            function () {
                return new UserRecoveryRepository(new UserRecoveryModel(), new UserRecoveryEntity());
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
            __DIR__.'/../Config/config.php' => config_path('user.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'user'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/user');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/user';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'user'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/user');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'user');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'user');
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
