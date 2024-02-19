<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Data;

use App\Modules\Salary\Enums\Level;
use Spatie\LaravelData\DataCollection;

/**
 * Данные для обновления раздела.
 */
class SectionUpdate extends SectionCreate
{
    /**
     * ID раздела.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID раздела.
     * @param string|null $name Название.
     * @param string|null $header Заголовка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param Level|null $level Уровень.
     * @param bool|null $free Призак бесплатности.
     * @param bool|null $status Статус.
     * @param string|null $description Описание.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title Заголовок.
     * @param ?DataCollection $items Элементы.
     */
    public function __construct(
        int|string      $id,
        ?string         $name = null,
        ?string         $header = null,
        ?string         $text = null,
        ?string         $additional = null,
        ?Level          $level = null,
        ?bool           $free = null,
        ?bool           $status = null,
        ?string         $description = null,
        ?string         $keywords = null,
        ?string         $title = null,
        ?DataCollection $items = null,
    )
    {
        $this->id = $id;

        parent::__construct(
            $name,
            $header,
            $text,
            $additional,
            $level,
            $free,
            $status,
            $description,
            $keywords,
            $title,
            $items,
        );
    }
}
