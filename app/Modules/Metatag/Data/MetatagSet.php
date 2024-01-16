<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Data;

use App\Models\Data;

/**
 * Данные для становки метатэгов.
 */
class MetatagSet extends Data
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Описание.
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
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Шаблон заголовок.
     *
     * @var string|null
     */
    public ?string $title_template = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $description_template = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $description Описание.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title Заголовок.
     * @param string|null $title_template Шаблон заголовок.
     * @param string|null $description_template Шаблон описания.
     */
    public function __construct(
        int|string|null $id = null,
        ?string $description = null,
        ?string $keywords = null,
        ?string $title = null,
        ?string $title_template = null,
        ?string $description_template = null
    )
    {
        $this->id = $id;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->title = $title;
        $this->title_template = $title_template;
        $this->description_template = $description_template;
    }
}
