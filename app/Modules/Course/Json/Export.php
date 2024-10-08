<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json;

use Carbon\Carbon;
use Storage;
use App\Models\Event;
use App\Modules\Course\Json\Sources\SchoolsSource;
use App\Modules\Course\Json\Sources\DirectionsSource;
use App\Modules\Course\Json\Sources\CourseDirectionSource;
use App\Modules\Course\Json\Sources\CourseProfessionSource;
use App\Modules\Course\Json\Sources\CourseSchoolSource;
use App\Modules\Course\Json\Sources\CourseSkillSource;
use App\Modules\Course\Json\Sources\CourseTeacherSource;
use App\Modules\Course\Json\Sources\CourseToolSource;
use App\Modules\Course\Json\Sources\CourseAllSource;
use App\Modules\Course\Json\Sources\CourseCategorySource;
use App\Modules\Course\Json\Sources\CourseSource;
use App\Modules\Course\Json\Sources\FaqsSource;
use App\Modules\Course\Json\Sources\ReviewsSource;
use App\Modules\Course\Json\Sources\CategorySource;
use App\Modules\Course\Json\Sources\DirectionSource;
use App\Modules\Course\Json\Sources\ProfessionSource;
use App\Modules\Course\Json\Sources\SchoolSource;
use App\Modules\Course\Json\Sources\SkillSource;
use App\Modules\Course\Json\Sources\TeacherSource;
use App\Modules\Course\Json\Sources\ToolSource;
use App\Modules\Course\Json\Sources\RatedCoursesSource;
use App\Modules\Course\Json\Sources\SectionSource;
use App\Modules\Course\Json\Sources\PublicationsSource;
use App\Modules\Course\Json\Sources\PublicationSource;
use App\Modules\Course\Json\Sources\CollectionsSource;
use App\Modules\Course\Json\Sources\CollectionSource;
use App\Modules\Course\Json\Sources\CourseStatSource;
use App\Modules\Course\Json\Sources\PromoSource;
use App\Modules\Course\Json\Sources\PromosSource;
use App\Modules\Course\Json\Sources\TeachersSource;
use App\Modules\Course\Json\Sources\ProfessionsSource;
use App\Modules\Course\Json\Sources\SkillsSource;
use App\Modules\Course\Json\Sources\ToolsSource;
use App\Modules\Course\Json\Sources\CategoriesSource;

/**
 * Класс для экспортирования курсов в файлы json.
 */
class Export
{
    use Event;

    /**
     * Количество дней, когда файл считается устаревшим.
     *
     * @var int
     */
    const int OLD_FILE_IN_DAYS = 2;

    /**
     * Источники.
     *
     * @var Source[]
     */
    private array $sources = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addSource(new TeachersSource())
            ->addSource(new SkillsSource())
            ->addSource(new ToolsSource())
            ->addSource(new ProfessionsSource())
            ->addSource(new CategoriesSource())
            ->addSource(new PromosSource())
            ->addSource(new PromoSource())
            ->addSource(new CourseStatSource())
            ->addSource(new CollectionsSource())
            ->addSource(new CollectionSource())
            ->addSource(new PublicationsSource())
            ->addSource(new PublicationSource())
            ->addSource(new SectionSource())
            ->addSource(new RatedCoursesSource())
            ->addSource(new ToolSource())
            ->addSource(new TeacherSource())
            ->addSource(new SkillSource())
            ->addSource(new SchoolSource())
            ->addSource(new ProfessionSource())
            ->addSource(new DirectionSource())
            ->addSource(new CategorySource())
            ->addSource(new ReviewsSource())
            ->addSource(new FaqsSource())
            ->addSource(new CourseSource())
            ->addSource(new SchoolsSource())
            ->addSource(new DirectionsSource())
            ->addSource(new CourseCategorySource())
            ->addSource(new CourseAllSource())
            ->addSource(new CourseDirectionSource())
            ->addSource(new CourseSchoolSource())
            ->addSource(new CourseProfessionSource())
            ->addSource(new CourseSkillSource())
            ->addSource(new CourseToolSource())
            ->addSource(new CourseTeacherSource());
    }

    /**
     * Получить количество генерируемых элементов.
     *
     * @return int Общее количество генерируемых элементов в файле
     */
    public function getTotal(): int
    {
        $sources = $this->getSources();
        $total = 0;

        foreach ($sources as $source) {
            $total += $source->count();
        }

        return $total;
    }

    /**
     * Запуск процесса экспортирование данных в файлы.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->exports();
        $this->clean();
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Экспортирование всех источников.
     *
     * @return void
     */
    private function exports(): void
    {
        $sources = $this->getSources();

        foreach ($sources as $source) {
            $source->addEvent('export', function () {
                $this->fireEvent('export');
            });

            $source->delete();
            $source->export();
        }
    }

    /**
     * Удаление старых файлов.
     *
     * @return void
     */
    private function clean(): void
    {
        $this->fireEvent('clean');

        $files = Storage::drive('public')->allFiles('/json');

        foreach ($files as $file) {
            $dateUpdate = Carbon::createFromTimestamp(filemtime(Storage::drive('public')->path($file)));
            $dateNow = Carbon::now();

            $diffDays = $dateNow->diffInDays($dateUpdate);

            if ($diffDays > self::OLD_FILE_IN_DAYS) {
                Storage::drive('public')->delete($file);
            }
        }
    }

    /**
     * Добавление источника.
     *
     * @param Source $source Источник.
     * @return $this
     */
    public function addSource(Source $source): self
    {
        $this->sources[] = $source;

        return $this;
    }

    /**
     * Удаление всех источников.
     *
     * @return $this
     */
    public function clearSources(): self
    {
        $this->sources = [];

        return $this;
    }

    /**
     * Получение всех источников.
     *
     * @return Source[]
     */
    public function getSources(): array
    {
        return $this->sources;
    }
}
