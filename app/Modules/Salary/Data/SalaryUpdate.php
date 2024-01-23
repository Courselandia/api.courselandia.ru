<?php
/**
 * Модуль Зарплаты.
 * Этот модуль содержит все классы для работы с зарплатами.
 *
 * @package App\Modules\Salary
 */

namespace App\Modules\Salary\Data;

use App\Modules\Salary\Enums\Level;

/**
 * Данные для обновления зарплаты.
 */
class SalaryUpdate extends SalaryCreate
{
    /**
     * ID зарплаты.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID зарплаты.
     * @param int|null $profession_id ID профессии.
     * @param Level|null $level Уровень.
     * @param int|null $salary Зарплата.
     * @param bool|null $status Статус.
     */
    public function __construct(
        int|string $id,
        ?int       $profession_id = null,
        ?Level     $level = null,
        ?int       $salary = null,
        ?bool      $status = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $profession_id,
            $level,
            $salary,
            $status,
        );
    }
}
