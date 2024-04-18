<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Data;

use App\Models\Data;

/**
 * Данные для создания термина.
 */
class TermCreate extends Data
{
    /**
     * Вариант термина.
     *
     * @var string|null
     */
    public ?string $variant = null;

    /**
     * Термин.
     *
     * @var string|null
     */
    public ?string $term = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * @param string|null $variant Вариант термина.
     * @param string|null $term Термин.
     * @param bool|null $status Статус.
     */
    public function __construct(
        ?string $variant = null,
        ?string $term = null,
        ?bool $status = null,
    )
    {
        $this->variant = $variant;
        $this->term = $term;
        $this->status = $status;
    }
}
