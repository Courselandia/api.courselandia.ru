<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Data;

use App\Models\Data;
use Carbon\Carbon;

/**
 * Данные для опыта учителя.
 */
class TeacherExperience extends Data
{
    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $teacher_id = null;

    /**
     * Место работы.
     *
     * @var ?string
     */
    public ?string $place = null;

    /**
     * Должность.
     *
     * @var ?string
     */
    public ?string $position = null;

    /**
     * Дата начала работы.
     *
     * @var ?Carbon
     */
    public ?Carbon $started = null;

    /**
     * Дата окончания работы.
     *
     * @var ?Carbon
     */
    public ?Carbon $finished = null;

    /**
     * Вес.
     *
     * @var ?int
     */
    public ?int $weight = null;

    /**
     * @param int|string|null $teacher_id ID учителя.
     * @param string|null $place Место работы.
     * @param string|null $position Должность.
     * @param Carbon|null $started Дата начала работы.
     * @param Carbon|null $finished Дата окончания работы.
     * @param int|null $weight Вес.
     */
    public function __construct(
        int|string|null $teacher_id = null,
        ?string         $place = null,
        ?string         $position = null,
        ?Carbon         $started = null,
        ?Carbon         $finished = null,
        ?int            $weight = null
    )
    {
        $this->teacher_id = $teacher_id;
        $this->place = $place;
        $this->position = $position;
        $this->started = $started;
        $this->finished = $finished;
        $this->weight = $weight;
    }
}
