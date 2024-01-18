<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Faq\Data;

use App\Models\Data;

/**
 * Данные для создания FAQ.
 */
class FaqCreate extends Data
{
    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

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
     * @param int|null $school_id ID школы.
     * @param string|null $question Вопрос.
     * @param bool|null $status Ответ.
     */
    public function __construct(
        ?int    $school_id = null,
        ?string $question = null,
        ?bool   $status = null
    )
    {
        $this->school_id = $school_id;
        $this->question = $question;
        $this->status = $status;
    }
}
