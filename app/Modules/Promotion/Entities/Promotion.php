<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Entities;

use Carbon\Carbon;
use App\Models\Entity;
use App\Modules\School\Entities\School;

/**
 * Сущность для промоакции.
 */
class Promotion extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID школы.
     *
     * @var int|string|null
     */
    public int|string|null $school_id = null;

    /**
     * ID источника промоакции.
     *
     * @var string|null
     */
    public ?string $uuid = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Дата начала.
     *
     * @var ?Carbon
     */
    public ?Carbon $date_start = null;

    /**
     * Дата окончания.
     *
     * @var ?Carbon
     */
    public ?Carbon $date_end = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Признак того, что промоакция действует.
     *
     * @var bool|null
     */
    public ?bool $applicable = null;

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
     * Школа.
     *
     * @var School|null
     */
    public ?School $school = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $school_id ID школы.
     * @param string|null $uuid ID источника промоакции.
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param Carbon|null $date_start Дата начала.
     * @param Carbon|null $date_end Дата окончания.
     * @param bool|string|null $status Статус.
     * @param bool|null $applicable Признак того, что промоакция действует.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param School|null $school Школа.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $school_id = null,
        string|null $uuid = null,
        string|null $title = null,
        string|null $description = null,
        Carbon|null $date_start = null,
        Carbon|null $date_end = null,
        bool|string|null $status = null,
        bool|null $applicable = null,
        ?Carbon $created_at = null,
        ?Carbon $updated_at = null,
        ?Carbon $deleted_at = null,
        ?School $school = null,
    ) {
        $this->id = $id;
        $this->school_id = $school_id;
        $this->uuid = $uuid;
        $this->title = $title;
        $this->description = $description;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->status = $status;
        $this->applicable = $applicable;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->school = $school;
    }
}
