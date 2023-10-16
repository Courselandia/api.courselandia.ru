<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Yml;

/**
 * Сущность элемента программы.
 */
class ProgramItem
{
    /**
     * Название раздела.
     *
     * @var string
     */
    public string $unit;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $description;
}
