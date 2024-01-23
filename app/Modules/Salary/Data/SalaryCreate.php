<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Data;

use App\Models\Data;
use App\Modules\Salary\Enums\Level;

/**
 * Данные для создания зарплаты.
 */
class SalaryCreate extends Data
{
    /**
     * ID профессии.
     *
     * @var int|null
     */
    public ?int $profession_id = null;

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
     * @param int|null $profession_id ID профессии.
     * @param Level|null $level Уровень.
     * @param int|null $salary Зарплата.
     * @param bool|null $status Статус.
     */
    public function __construct(
        ?int   $profession_id = null,
        ?Level $level = null,
        ?int   $salary = null,
        ?bool  $status = null
    )
    {
        $this->profession_id = $profession_id;
        $this->level = $level;
        $this->salary = $salary;
        $this->status = $status;
    }
}
