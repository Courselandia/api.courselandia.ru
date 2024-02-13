<?php
/**
 * Модуль Как проходит обучение.
 * Этот модуль содержит все классы для работы с объяснением как проходит обучение.
 *
 * @package App\Modules\Process
 */

namespace App\Modules\Process\Data;

/**
 * Данные для обновления объяснения как проходит обучение.
 */
class ProcessUpdate extends ProcessCreate
{
    /**
     * ID объяснения как проходит обучение.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID объяснения как проходит обучение.
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

        parent::__construct($name, $text, $status);
    }
}
