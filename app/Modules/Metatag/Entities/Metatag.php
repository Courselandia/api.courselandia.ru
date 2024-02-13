<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для метатэгов.
 */
class Metatag extends Entity
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
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * @param int|string|null $id ID записи.
     * @param string|null $description Описание.
     * @param string|null $keywords Ключевые слова.
     * @param string|null $title Заголовок.
     * @param string|null $title_template Шаблон заголовок.
     * @param string|null $description_template Шаблон описания.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        ?string         $description = null,
        ?string         $keywords = null,
        ?string         $title = null,
        ?string         $title_template = null,
        ?string         $description_template = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->description = $description;
        $this->keywords = $keywords;
        $this->title = $title;
        $this->title_template = $title_template;
        $this->description_template = $description_template;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
