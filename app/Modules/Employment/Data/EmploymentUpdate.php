<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Data;

/**
 * Данные для обновления трудоустройства.
 */
class EmploymentUpdate extends EmploymentCreate
{
    /**
     * ID трудоустройства.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID трудоустройства.
     * @param string|null $name Название.
     * @param string|null $text Статья.
     * @param bool|null $status Статус.
     */
    public function __construct(
        int|string $id,
        ?string    $name = null,
        ?string    $text = null,
        ?bool      $status = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $name,
            $text,
            $status
        );
    }
}
