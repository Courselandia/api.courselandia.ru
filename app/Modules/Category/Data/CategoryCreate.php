<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Data;

use App\Models\Data;

/**
 * Данные для действия создание категории.
 */
class CategoryCreate extends Data
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
     * Дополнительное описание.
     *
     * @var string|null
     */
    public ?string $additional = null;

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
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * ID профессий.
     *
     * @var int[]
     */
    public ?array $professions;

    /**
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param string|null $link Ссылка.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     * @param array|null $directions ID направлений.
     * @param array|null $professions ID профессий.
     */
    public function __construct(
        ?string $name = null,
        ?string $header_template = null,
        ?string $text = null,
        ?string $additional = null,
        ?string $link = null,
        ?bool   $status = null,
        ?string $description_template = null,
        ?string $keywords = null,
        ?string $title_template = null,
        ?array  $directions = null,
        ?array  $professions = null
    )
    {
        $this->name = $name;
        $this->header_template = $header_template;
        $this->text = $text;
        $this->additional = $additional;
        $this->link = $link;
        $this->status = $status;
        $this->description_template = $description_template;
        $this->keywords = $keywords;
        $this->title_template = $title_template;
        $this->directions = $directions;
        $this->professions = $professions;
    }
}
