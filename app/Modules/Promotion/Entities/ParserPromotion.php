<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Entities;

use App\Models\Entity;
use App\Modules\School\Enums\School;
use Carbon\Carbon;

/**
 * Сущность для разобранной промоакции во время импорта.
 */
class ParserPromotion extends Entity
{
    /**
     * ID источника промоакции.
     *
     * @var string|null
     */
    public string|null $uuid = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public string|null $title = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public string|null $description = null;

    /**
     * Дата начала.
     *
     * @var Carbon|null
     */
    public Carbon|null $date_start = null;

    /**
     * Дата окончания.
     *
     * @var Carbon|null
     */
    public Carbon|null $date_end = null;

    /**
     * Ссылка на акцию.
     *
     * @var string|null
     */
    public string|null $url = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public bool|null $status = null;

    /**
     * Школа.
     *
     * @var School|null
     */
    public School|null $school = null;

    /**
     * @param string|null $uuid ID источника промоакции.
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param Carbon|null $date_start Дата начала.
     * @param Carbon|null $date_end Дата окончания.
     * @param string|null $url Ссылка на акцию.
     * @param bool|null $status Статус.
     * @param School|null $school Школа.
     */
    public function __construct(
        string|null $uuid = null,
        string|null $title = null,
        string|null $description = null,
        Carbon|null $date_start = null,
        Carbon|null $date_end = null,
        string|null $url = null,
        bool|null $status = null,
        School|null $school = null,
    ) {
        $this->uuid = $uuid;
        $this->title = $title;
        $this->description = $description;
        $this->date_start = $date_start;
        $this->date_end = $date_end;
        $this->url = $url;
        $this->status = $status;
        $this->school = $school;
    }
}
