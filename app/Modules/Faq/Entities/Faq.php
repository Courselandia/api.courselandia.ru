<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Entities;

use App\Models\Entity;
use App\Modules\School\Entities\School;
use Carbon\Carbon;

/**
 * Сущность для FAQ.
 */
class Faq extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID профессии.
     *
     * @var int|string|null
     */
    public int|string|null $school_id = null;

    /**
     * Вопрос.
     *
     * @var string|null
     */
    public ?string $question = null;

    /**
     * Ответ.
     *
     * @var string|null
     */
    public ?string $answer = null;

    /**
     * Статус.
     *
     * @var boolean|null
     */
    public ?bool $status = null;

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
     * Школа.
     *
     * @var School|null
     */
    public ?School $school = null;

    /**
     * @param int|string|null $id ID записи.
     * @param int|string|null $school_id ID профессии.
     * @param string|null $question Вопрос.
     * @param string|null $answer Ответ.
     * @param bool|null $status Статус.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     * @param School|null $school Школа.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $school_id = null,
        ?string         $question = null,
        ?string         $answer = null,
        ?bool           $status = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null,
        ?School         $school = null,
    )
    {
        $this->id = $id;
        $this->school_id = $school_id;
        $this->question = $question;
        $this->answer = $answer;
        $this->status = $status;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
        $this->school = $school;
    }
}
