<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Data;

/**
 * Данные для действия обновления категории.
 */
class CategoryUpdate extends CategoryCreate
{
    /**
     * Название.
     *
     * @var string|int
     */
    public string|int $id;

    /**
     * @param string|int $name ID категории.
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param string|null $text Статья.
     * @param string|null $link Ссылка.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     * @param array|null $directions ID направлений.
     * @param array|null $professions ID профессий.
     */
    public function __construct(
        string|int $id,
        ?string    $name = null,
        ?string    $header_template = null,
        ?string    $text = null,
        ?string    $link = null,
        ?bool      $status = null,
        ?string    $description_template = null,
        ?string    $keywords = null,
        ?string    $title_template = null,
        ?array     $directions = null,
        ?array     $professions = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $name,
            $header_template,
            $text,
            $link,
            $status,
            $description_template,
            $keywords,
            $title_template,
            $directions,
            $professions
        );
    }
}
