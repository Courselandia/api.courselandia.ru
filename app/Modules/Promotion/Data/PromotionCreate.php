<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Data;

use App\Models\Data;
use Carbon\Carbon;

/**
 * Данные для создания промоакции.
 */
class PromotionCreate extends Data
{
    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

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
     * Ссылка на акцию.
     *
     * @var ?string
     */
    public ?string $url = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * @param int|null $school_id ID школы.
     * @param string|null $uuid ID источника промоакции.
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param Carbon|null $date_start Дата начала.
     * @param Carbon|null $date_end Дата окончания.
     * @param string|null $url Ссылка на акцию.
     * @param bool|null $status Статус.
     */
    public function __construct(
        ?int $school_id = null,
        ?string $uuid = null,
        ?string $title = null,
        ?string $description = null,
        ?Carbon $date_start = null,
        ?Carbon $date_end = null,
        ?string $url = null,
        ?bool $status = null,
    ) {
        $this->school_id = $school_id;
        $this->uuid = $uuid;
        $this->title = $title;
        $this->description = $description;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->url = $url;
        $this->status = $status;
    }
}
