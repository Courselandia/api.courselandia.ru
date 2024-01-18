<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Data;

use App\Models\Data;

/**
 * Данные для создания трудоустройства.
 */
class EmploymentCreate extends Data
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * @param string|null $name Название.
     * @param string|null $text Статья.
     * @param bool|null $status Статус.
     */
    public function __construct(
        ?string $name = null,
        ?string $text = null,
        ?bool   $status = null
    )
    {
        $this->name = $name;
        $this->text = $text;
        $this->status = $status;
    }
}
