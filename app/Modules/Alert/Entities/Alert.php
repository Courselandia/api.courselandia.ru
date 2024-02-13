<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для предупреждения.
 */
class Alert extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * URL.
     *
     * @var string|null
     */
    public ?string $url = null;

    /**
     * Тэг.
     *
     * @var string|null
     */
    public ?string $tag = null;

    /**
     * Цвет.
     *
     * @var string|null
     */
    public ?string $color = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * @param string|null $title Название.
     * @param string|null $description Описание.
     * @param string|null $url URL.
     * @param string|null $tag Тэг.
     * @param string|null $color Цвет.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $title = null,
        ?string         $description = null,
        ?string         $url = null,
        ?string         $tag = null,
        ?string         $color = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->tag = $tag;
        $this->color = $color;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
