<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile;

use App\Modules\Course\Entities\CourseRead;
use Storage;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\DbFile\Sources\SourceDirection;

/**
 * Класс для экспортирования курсов в файлы для их быстрой загрузки.
 */
class Export
{
    use Event;
    use Error;

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
        $this->addSource(new SourceDirection());
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
        $this->export();
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
    private function export(): void
    {
        $sources = $this->getSources();

        foreach ($sources as $source) {
            foreach ($source->read() as $item) {
                $this->save($source->getPathToDir(), $item->id, $item->data);

                $this->fireEvent(
                    'read',
                    [
                        $source->getPathToDir(),
                        $item->id,
                        $item->data,
                    ]
                );
            }

            if ($source->hasError()) {
                $errors = $source->getErrors();

                foreach ($errors as $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Сохранение данных в файл.
     *
     * @param string $pathToDir Путь к файлу.
     * @param string|int $id ID данных.
     * @param CourseRead $data Данные для сохранения в файл.
     *
     * @return void
     */
    private function save(string $pathToDir, string|int $id, CourseRead $data): void
    {
        Storage::drive('local')->put('/db/' . $pathToDir . '/' . $id . '.obj', serialize($data));
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
