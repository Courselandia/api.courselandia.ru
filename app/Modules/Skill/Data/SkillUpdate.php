<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Data;

/**
 * Данные для создания навыка.
 */
class SkillUpdate extends SkillCreate
{
    /**
     * ID навыка.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID навыка.
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
        int|string $id,
        ?string    $name = null,
        ?string    $header_template = null,
        ?string    $link = null,
        ?string    $text = null,
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
            $link,
            $text,
            $status,
            $description_template,
            $keywords,
            $title_template,
        );
    }
}
