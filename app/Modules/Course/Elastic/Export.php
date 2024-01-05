<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Elastic;

use Cache;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\Elastic\Sources\SourceCourse;

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
        $this
            ->addSource(new SourceCourse());
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
