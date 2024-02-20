<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Data;

use App\Models\Data;
use App\Modules\Salary\Enums\Level;

/**
 * Данные для создания раздела.
 */
class SectionCreate extends Data
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Дополнительное описание.
     *
     * @var string|null
     */
    public ?string $additional = null;

    /**
     * Уровень.
     *
     * @var Level|null
     */
    public ?Level $level = null;

    /**
     * Признак бесплатности.
     *
     * @var bool|null
     */
    public ?bool $free = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Описания.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовка.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Элементы.
     *
     * @var array|null
     */
    public ?array $items = null;

    /**
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
     * @param array $items Элементы.
     */
    public function __construct(
        ?string $name = null,
        ?string $header = null,
        ?string $text = null,
        ?string $additional = null,
        ?Level  $level = null,
        ?bool   $free = null,
        ?bool   $status = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $title = null,
        ?array   $items = null,
    )
    {
        $this->name = $name;
        $this->header = $header;
        $this->text = $text;
        $this->additional = $additional;
        $this->level = $level;
        $this->free = $free;
        $this->status = $status;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->title = $title;
        $this->items = $items;
    }
}
