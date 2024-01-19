<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply;

use App\Models\Error;

/**
 * Абстрактный класс задание для назначения метатэгов.
 */
abstract class Task
{
    use Error;

    /**
     * Признак того, что нужно только обновить мэтатеги на основе уже введенных шаблонов.
     *
     * @var bool
     */
    public bool $update = false;

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    abstract public function count(): int;

    /**
     * Применяем метатэги.
     *
     * @param Callable|null $read Метод, который будет вызван каждый раз при генерации метатэга.
     *
     * @return void
     */
    abstract public function apply(?callable $read = null): void;

    /**
     * Установит или получит признак того, нужно ли только обновлять мэтатэги.
     *
     * @param ?bool $status Если указать, то изменит параметр.
     *
     * @return bool Признак обновления.
     */
    public function onlyUpdate(?bool $status = null): bool
    {
        if ($status !== null) {
            $this->update = $status;
        }

        return $this->update;
    }
}
