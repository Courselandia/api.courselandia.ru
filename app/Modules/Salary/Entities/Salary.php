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
}
