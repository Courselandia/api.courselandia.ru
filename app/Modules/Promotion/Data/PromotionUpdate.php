<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Data;

use Carbon\Carbon;

/**
 * Данные для обновления промоакции.
 */
class PromotionUpdate extends PromotionCreate
{
    /**
     * ID промоакции.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int $id ID промоакции.
     * @param int|null $school_id ID школы.
     * @param string|null $uuid ID источника промоакции.
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param Carbon|null $date_start Дата начала.
     * @param Carbon|null $date_end Дата окончания.
     * @param bool|null $status Статус.
     */
    public function __construct(
        int $id,
        ?int $school_id = null,
        ?string $uuid = null,
        ?string $title = null,
        ?string $description = null,
        ?Carbon $date_start = null,
        ?Carbon $date_end = null,
        ?bool $status = null,
    ) {
        $this->id = $id;

        parent::__construct($school_id, $uuid, $title, $description, $date_start, $date_end, $status);
    }
}
