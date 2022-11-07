<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Providers;

use App;
use Config;
use Illuminate\Support\ServiceProvider;

use App\Modules\Course\Models\Course as CourseModel;
use App\Modules\Course\Repositories\Course as CourseRepository;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Events\Listeners\CourseListener;

use App\Modules\Course\Models\CourseEmployment as CourseEmploymentModel;
use App\Modules\Course\Repositories\CourseEmployment as CourseEmploymentRepository;
use App\Modules\Course\Entities\CourseEmployment as CourseEmploymentEntity;

use App\Modules\Course\Models\CourseFeature as CourseFeatureModel;
use App\Modules\Course\Repositories\CourseFeature as CourseFeatureRepository;
use App\Modules\Course\Entities\CourseFeature as CourseFeatureEntity;

use App\Modules\Course\Models\CourseLearn as CourseLearnModel;
use App\Modules\Course\Repositories\CourseLearn as CourseLearnRepository;
use App\Modules\Course\Entities\CourseLearn as CourseLearnEntity;

use App\Modules\Course\Models\CourseLevel as CourseLevelModel;
use App\Modules\Course\Repositories\CourseLevel as CourseLevelRepository;
use App\Modules\Course\Entities\CourseLevel as CourseLevelEntity;

/**
 * Класс сервис-провайдера для настройки этого модуля.
 */
class CourseServiceProvider extends ServiceProvider
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

        CourseModel::observe(CourseListener::class);
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
            CourseRepository::class,
            function () {
                return new CourseRepository(new CourseModel(), new CourseEntity());
            }
        );

        App::singleton(
            CourseEmploymentRepository::class,
            function () {
                return new CourseEmploymentRepository(new CourseEmploymentModel(), new CourseEmploymentEntity());
            }
        );

        App::singleton(
            CourseFeatureRepository::class,
            function () {
                return new CourseFeatureRepository(new CourseFeatureModel(), new CourseFeatureEntity());
            }
        );

        App::singleton(
            CourseLearnRepository::class,
            function () {
                return new CourseLearnRepository(new CourseLearnModel(), new CourseLearnEntity());
            }
        );

        App::singleton(
            CourseLevelRepository::class,
            function () {
                return new CourseLevelRepository(new CourseLevelModel(), new CourseLevelEntity());
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
            __DIR__.'/../Config/config.php' => config_path('course.php'),
        ]);
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php',
            'course'
        );
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    public function registerViews(): void
    {
        $viewPath = base_path('resources/views/modules/course');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);

        $this->loadViewsFrom(
            array_merge(
                array_map(function ($path) {
                    return $path.'/modules/course';
                }, Config::get('view.paths')),
                [$sourcePath]
            ),
            'course'
        );
    }

    /**
     * Регистрация локалей.
     *
     * @return void
     */
    public function registerTranslations(): void
    {
        $langPath = base_path('resources/lang/modules/course');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'course');
        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'course');
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
