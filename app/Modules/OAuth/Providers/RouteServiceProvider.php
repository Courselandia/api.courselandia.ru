<?php
/**
 * Модуль API аутентификации.
 * Этот модуль содержит все классы для работы с API аутентификации.
 *
 * @package App\Modules\OAuth
 */

namespace App\Modules\OAuth\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Класс сервис-провайдера для настройки путей этого модуля.
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * Пространство имен модуля, которое предполагается использовать при создании URL-адресов для действий.
     *
     * @var string
     */
    protected string $moduleNamespace = 'App\Modules\OAuth\Http\Controllers';

    /**
     * Вызвать до того как пути будут зарегистрированы.
     *
     * Зарегистрируйте любые привязки моделей или шаблонные фильтры.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Определяем пути для приложения.
     *
     * @return void
     */
    public function map(): void
    {
        $this->mapApiRoutes();
        // $this->mapWebRoutes();
    }

    /**
     * Определяем пути "web" для приложения.
     *
     * Эти пути получают сессии, защиту от кросс домен атак и т.д.
     *
     * @return void
     */
    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(__DIR__ . '/../Routes/web.php');
    }

    /**
     * Определяем пути для "API" для приложения.
     *
     * Эти пути ничем не нагружены.
     *
     * @return void
     */
    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->moduleNamespace)
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
