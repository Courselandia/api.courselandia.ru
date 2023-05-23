<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Export;

use App\Modules\Course\Export\Sources\CourseAllSource;
use App\Modules\Course\Models\CourseMongoDb;
use Cache;
use App\Models\Event;
use App\Modules\Course\Export\Sources\CourseSource;
use App\Modules\Course\Export\Sources\CourseDirectionSource;
use App\Modules\Course\Export\Sources\CourseCategorySource;
use App\Modules\Course\Export\Sources\CourseProfessionSource;
use App\Modules\Course\Export\Sources\CourseSchoolSource;
use App\Modules\Course\Export\Sources\CourseSkillSource;
use App\Modules\Course\Export\Sources\CourseTeacherSource;
use App\Modules\Course\Export\Sources\CourseToolSource;
use App\Modules\Course\Export\Jobs\CourseAllItemJob;

/**
 * Класс для экспортирования курсов в MongoDB для быстрой загрузки.
 */
class Export
{
    use Event;

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
        $this->addSource(new CourseAllSource())
            ->addSource(new CourseDirectionSource())
            ->addSource(new CourseSchoolSource())
            ->addSource(new CourseCategorySource())
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
        Cache::flush();

        $this->offLimits();
        $this->truncate();
        $this->exports();
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
     * Очистить старые данные.
     *
     * @return void
     */
    private function truncate(): void
    {
        CourseMongoDb::truncate();
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

            $source->export();
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
