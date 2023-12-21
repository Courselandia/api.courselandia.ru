<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json;

use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\Imports\Parser;

/**
 * Абстрактный класс для создания источника формирования JSON файла.
 */
abstract class Source
{
    use Event;

    /**
     * Массив ID активных сущностей.
     *
     * @var int[]|string[]
     */
    private array $ids = [];

    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    abstract public function count(): int;

    /**
     * Запуск экспорта данных.
     *
     * @return void.
     */
    abstract public function export(): void;

    /**
     * Запуск удаления не активных данных.
     *
     * @return void.
     */
    public function delete(): void
    {

    }

    /**
     * Добавление ID обновленной сущности.
     *
     * @param int|string $id ID сущности.
     * @return $this
     */
    protected function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных сущностей.
     *
     * @return $this
     */
    protected function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных сущностей.
     *
     * @return Parser[]
     */
    protected function getIds(): array
    {
        return $this->ids;
    }
}
