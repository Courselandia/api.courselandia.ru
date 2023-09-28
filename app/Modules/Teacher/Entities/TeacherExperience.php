<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для опыта работы учителя.
 */
class TeacherExperience extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID чителя.
     *
     * @var int|string|null
     */
    public int|string|null $teacher_id = null;

    /**
     * Место работы.
     *
     * @var string|null
     */
    public ?string $place = null;

    /**
     * Должность.
     *
     * @var string|null
     */
    public ?string $position = null;

    /**
     * Дата начала работы.
     *
     * @var Carbon|null
     */
    public ?Carbon $started = null;

    /**
     * Дата окончания работы.
     *
     * @var Carbon|null
     */
    public ?Carbon $finished = null;

    /**
     * Вес.
     *
     * @var int|null
     */
    public ?float $weight = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;
}
