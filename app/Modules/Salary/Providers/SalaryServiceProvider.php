<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Providers;

use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Salary\Models\Salary as SalaryModel;
use App\Modules\Salary\Events\Listeners\SalaryListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class SalaryServiceProvider extends ServiceProvider
{
    /**
     * Обработчик события загрузки приложения.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerTranslations();
        // $this->registerConfig();

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        SalaryModel::observe(SalaryListener::class);
    }

    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Регистрация настроек.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->publishes([
            __DIR__ . '/../Config/config.php' => config_path('salary.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php',
            'salary'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/salary');

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path . '/modules/salary';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'salary'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/salary');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'salary');
        } else {
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'salary');
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
