<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Data;

/**
 * Данные для создания навыка.
 */
class TermUpdate extends TermCreate
{
    /**
     * ID навыка.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID термина.
     * @param string|null $variant Вариант термина.
     * @param string|null $term Термин.
     * @param bool|null $status Статус.
     */
    public function __construct(
        int|string $id,
        ?string    $variant = null,
        ?string    $term = null,
        ?bool      $status = null,
    )
    {
        $this->id = $id;

        parent::__construct(
            $variant,
            $term,
            $status,
        );
    }
}
