<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Entities;

use App\Models\Entity;
use App\Modules\Profession\Entities\Profession;
use App\Modules\Salary\Enums\Level;
use Carbon\Carbon;

/**
 * Сущность для зарплат.
 */
class Salary extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID профессии.
     *
     * @var int|string|null
     */
    public int|string|null $profession_id = null;

    /**
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;

    /**
     * Зарплата.
     *
     * @var int|null
     */
    public ?int $salary = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Профессия.
     *
     * @var Profession|null
     */
    public ?Profession $profession = null;

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
     * @param int|string|null $profession_id ID профессии.
     * @param Level|null $level Уровень.
     * @param int|null $salary Зарплата.
     * @param bool|null $status Статус.
     * @param Profession|null $profession Профессия.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $profession_id = null,
        ?Level          $level = null,
        ?int            $salary = null,
        ?bool           $status = null,
        ?Profession     $profession = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->profession_id = $profession_id;
        $this->level = $level;
        $this->salary = $salary;
        $this->status = $status;
        $this->profession = $profession;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
