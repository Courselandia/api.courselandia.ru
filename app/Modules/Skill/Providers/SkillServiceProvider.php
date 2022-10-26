<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Skill\Models\Skill as SkillModel;
use App\Modules\Skill\Repositories\Skill as SkillRepository;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Events\Listeners\SkillListener;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class SkillServiceProvider extends ServiceProvider
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

        SkillModel::observe(SkillListener::class);
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
            SkillRepository::class,
            function () {
                return new SkillRepository(new SkillModel(), new SkillEntity());
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
            __DIR__.'/../Config/config.php' => config_path('skill.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'skill'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/skill');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/skill';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'skill'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/skill');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'skill');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'skill');
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
