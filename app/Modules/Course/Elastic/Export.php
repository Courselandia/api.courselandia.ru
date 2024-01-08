<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic;

use Cache;
use Throwable;
use Elasticsearch;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\Elastic\Sources\SourceCourse;
use App\Modules\Course\Elastic\Sources\SourceSkill;
use App\Modules\Course\Elastic\Sources\SourceTool;
use App\Modules\Course\Elastic\Sources\SourceCategory;
use App\Modules\Course\Elastic\Sources\SourceDirection;
use App\Modules\Course\Elastic\Sources\SourceProfession;
use App\Modules\Course\Elastic\Sources\SourceTeacher;
use App\Modules\Course\Elastic\Sources\SourceSchool;

/**
 * Экспортируем данные курсов в Elasticsearch.
 */
class Export
{
    use Event;
    use Error;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addSource(new SourceCourse())
            ->addSource(new SourceDirection())
            ->addSource(new SourceSchool())
            ->addSource(new SourceSkill())
            ->addSource(new SourceTool())
            ->addSource(new SourceCategory())
            ->addSource(new SourceProfession())
            ->addSource(new SourceTeacher())
        ;
    }

    /**
     * Набор источников, которые должны быть импортированы.
     *
     * @var array
     */
    private array $sources = [];

    /**
     * Запуск импортирования.
     *
     * @return void
     */
    public function run(): void
    {
        Cache::flush();

        $this->offLimits();
        $this->exports();
    }

    /**
     * Удаление всех индексов.
     *
     * @return void
     */
    public function clean(): void
    {
        $sources = $this->getSources();

        foreach ($sources as $source) {
            $exist = Elasticsearch::indices()->exists([
                'index' => $source->name(),
            ]);

            if ($exist) {
                try {
                    Elasticsearch::indices()->delete(['index' => $source->name()]);
                } catch (Throwable $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Получение общего количества импортируемых записей.
     *
     * @return int Количество импортируемых записей.
     */
    public function count(): int
    {
        $count = 0;
        $sources = $this->getSources();

        foreach ($sources as $source) {
            $count += $source->count();
        }

        return $count;
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

            if ($source->hasError()) {
                foreach ($source->getErrors() as $error) {
                    $this->addError($error);
                }
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
     * @return Source[] Источники.
     */
    public function getSources(): array
    {
        return $this->sources;
    }
}
