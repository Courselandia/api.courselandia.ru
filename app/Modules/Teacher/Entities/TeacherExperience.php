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
     * ID учителя.
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

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $teacher_id ID учителя.
     * @param string|null $place Место работы.
     * @param string|null $position Должность.
     * @param Carbon|null $started Дата начала работы.
     * @param Carbon|null $finished Дата окончания работы.
     * @param float|null $weight Вес.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $teacher_id = null,
        ?string         $place = null,
        ?string         $position = null,
        ?Carbon         $started = null,
        ?Carbon         $finished = null,
        ?float          $weight = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->teacher_id = $teacher_id;
        $this->place = $place;
        $this->position = $position;
        $this->started = $started;
        $this->finished = $finished;
        $this->weight = $weight;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
