<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile;

use App\Models\Event;
use App\Modules\Course\DbFile\Sources\SourceCourse;
use App\Modules\Course\DbFile\Sources\SourceDirection;
use App\Modules\Course\DbFile\Sources\SourceCategory;
use App\Modules\Course\DbFile\Sources\SourceProfession;
use App\Modules\Course\DbFile\Sources\SourceSchool;
use App\Modules\Course\DbFile\Sources\SourceSkill;
use App\Modules\Course\DbFile\Sources\SourceTeacher;
use App\Modules\Course\DbFile\Sources\SourceTool;

/**
 * Класс для экспортирования курсов в файлы для их быстрой загрузки.
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
        $this/*->addSource(new SourceCourse())*/
            ->addSource(new SourceDirection())
            /*->addSource(new SourceSchool())
            ->addSource(new SourceCategory())
            ->addSource(new SourceProfession())
            ->addSource(new SourceSkill())
            ->addSource(new SourceTool())
            ->addSource(new SourceTeacher())*/;
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
