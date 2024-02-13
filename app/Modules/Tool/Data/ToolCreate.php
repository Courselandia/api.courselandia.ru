<?php
/**
 * Модуль Инструментов.
 * Этот модуль содержит все классы для работы с инструментами.
 *
 * @package App\Modules\Tool
 */

namespace App\Modules\Tool\Data;

use App\Models\Data;

/**
 * Данные для создания инструмента.
 */
class ToolCreate extends Data
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

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
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $description_template = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $title_template = null;

    /**
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     */
    public function __construct(
        ?string $name = null,
        ?string $header_template = null,
        ?string $link = null,
        ?string $text = null,
        ?bool   $status = null,
        ?string $description_template = null,
        ?string $keywords = null,
        ?string $title_template = null,
    )
    {
        $this->name = $name;
        $this->header_template = $header_template;
        $this->link = $link;
        $this->text = $text;
        $this->status = $status;
        $this->description_template = $description_template;
        $this->keywords = $keywords;
        $this->title_template = $title_template;
    }
}
