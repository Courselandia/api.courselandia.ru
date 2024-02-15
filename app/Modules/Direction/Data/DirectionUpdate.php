<?php
/**
 * Модуль Направления.
 * Этот модуль содержит все классы для работы с направлениями.
 *
 * @package App\Modules\Direction
 */

namespace App\Modules\Direction\Data;

/**
 * Данные для обновления направления.
 */
class DirectionUpdate extends DirectionCreate
{
    /**
     * ID направления.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param string|int $id ID направления.
     * @param string|null $name Название.
     * @param string|null $header_template Шаблон заголовка.
     * @param int|null $weight Вес.
     * @param string|null $link Ссылка.
     * @param string|null $text Статья.
     * @param string|null $additional Дополнительное описание.
     * @param bool|null $status Статус.
     * @param string|null $description_template Шаблон описания.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title_template Шаблон заголовка.
     */
    public function __construct(
        int|string $id,
        ?string    $name = null,
        ?string    $header_template = null,
        ?int       $weight = null,
        ?string    $link = null,
        ?string    $text = null,
        ?string    $additional = null,
        ?bool      $status = null,
        ?string    $description_template = null,
        ?string    $keywords = null,
        ?string    $title_template = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $name,
            $header_template,
            $weight,
            $link,
            $text,
            $additional,
            $status,
            $description_template,
            $keywords,
            $title_template
        );
    }
}
