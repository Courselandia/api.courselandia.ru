<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Normalize;

use App\Models\Error;
use App\Models\Event;
use App\Modules\Image\Normalize\Workers\CourseWorker;
use App\Modules\Image\Normalize\Workers\SchoolWorker;
use App\Modules\Image\Normalize\Workers\TeacherWorker;

/**
 * Нормализация изображений.
 */
class Normalize
{
    use Event;
    use Error;

    /**
     * Воркеры для нормализации.
     *
     * @var array<Worker>
     */
    private array $workers = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addWorker(new TeacherWorker())
            ->addWorker(new CourseWorker())
            ->addWorker(new SchoolWorker());
    }

    /**
     * Получить количество нормализуемых элементов.
     *
     * @return int Общее количество нормализуемых элементов в файле
     */
    public function getTotal(): int
    {
        $workers = $this->getWorkers();
        $total = 0;

        foreach ($workers as $worker) {
            $total += $worker->total();
        }

        return $total;
    }

    /**
     * Запуск нормализации.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->do();
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
     * Проводим нормализацию.
     *
     * @return void
     */
    private function do(): void
    {
        $workers = $this->getWorkers();

        foreach ($workers as $worker) {
            $worker->addEvent('normalized', function ($teacher) use ($worker) {
                $this->fireEvent('normalized', [$teacher, $worker]);
            });

            $worker->run();
        }
    }

    /**
     * Добавление воркера для нормализации.
     *
     * @param Worker $worker Воркер для нормализации.
     * @return $this
     */
    public function addWorker(Worker $worker): self
    {
        $this->workers[] = $worker;

        return $this;
    }

    /**
     * Удаление воркера для нормализации.
     *
     * @return $this
     */
    public function clearWorkers(): self
    {
        $this->workers = [];

        return $this;
    }

    /**
     * Получение всех воркеров для нормализации.
     *
     * @return Worker[]
     */
    public function getWorkers(): array
    {
        return $this->workers;
    }
}